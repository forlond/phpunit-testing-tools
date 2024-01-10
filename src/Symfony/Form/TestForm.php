<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Form;

use Forlond\TestTools\AbstractTest;
use Forlond\TestTools\Exception\TestFailedException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Form\FormInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestForm extends AbstractTest
{
    /**
     * @var array<TestForm|bool>
     */
    private array $children = [];

    private bool $childAssertion = true;

    private ?TestFormErrors $errors = null;

    public function __construct(
        private readonly FormInterface $form,
    ) {
    }

    public function disableChildAssertion(): self
    {
        $this->childAssertion = false;

        return $this;
    }

    public function type(Constraint|string $value): self
    {
        if (is_string($value)) {
            $value = new IsInstanceOf($value);
        }

        $this->set('type', $value, static fn(FormInterface $form) => $form->getConfig()->getType());

        return $this;
    }

    public function options(Constraint|array $value): self
    {
        $this->set('options', $value, static fn(FormInterface $form) => $form->getConfig()->getOptions());

        return $this;
    }

    public function option(string $name, mixed $value, mixed $default = null): self
    {
        $this->set(
            sprintf('options.%s', $name),
            $value,
            static fn(FormInterface $form) => $form->getConfig()->getOption($name, $default)
        );

        return $this;
    }

    public function required(bool $value): self
    {
        $this->set('required', $value, static fn(FormInterface $form) => $form->isRequired());

        return $this;
    }

    public function disabled(bool $value): self
    {
        $this->set('disabled', $value, static fn(FormInterface $form) => $form->isDisabled());

        return $this;
    }

    public function isEmpty(bool $value): self
    {
        $this->set('isEmpty', $value, static fn(FormInterface $form) => $form->isEmpty());

        return $this;
    }

    public function propertyPath(Constraint|string $value): self
    {
        $this->set('propertyPath', $value, static fn(FormInterface $form) => $form->getPropertyPath()?->__toString());

        return $this;
    }

    public function errors(callable $expect): self
    {
        if (null !== $this->errors) {
            throw new \RuntimeException('Cannot redefine errors');
        }

        $test = new TestFormErrors($this->form->getErrors());
        $expect($test);

        $this->errors = $test;

        return $this;
    }

    public function data(mixed $value): self
    {
        $this->set('data', $value, static fn(FormInterface $form) => $form->getData());

        return $this;
    }

    public function normData(mixed $value): self
    {
        $this->set('normData', $value, static fn(FormInterface $form) => $form->getNormData());

        return $this;
    }

    public function viewData(mixed $value): self
    {
        $this->set('viewData', $value, static fn(FormInterface $form) => $form->getViewData());

        return $this;
    }

    public function extraData(mixed $value): self
    {
        $this->set('extraData', $value, static fn(FormInterface $form) => $form->getExtraData());

        return $this;
    }

    public function valid(bool $value): self
    {
        if (!$this->form->isSubmitted()) {
            throw new \RuntimeException('Cannot use valid() if the form was not submitted.');
        }

        $this->set('valid', $value, static fn(FormInterface $form) => $form->isValid());

        return $this;
    }

    public function submitted(bool $value): self
    {
        $this->set('submitted', $value, static fn(FormInterface $form) => $form->isSubmitted());

        return $this;
    }

    public function child(string $child, callable $expect): self
    {
        if (isset($this->children[$child])) {
            throw new \RuntimeException('Cannot redefine child ' . $child);
        }

        if ($this->form->has($child)) {
            $test = new self($this->form->get($child));
            $expect($test);
        } else {
            $test = true;
        }

        $this->children[$child] = $test;

        return $this;
    }

    public function absence(string $child): self
    {
        if (isset($this->children[$child])) {
            throw new \RuntimeException('Cannot redefine child ' . $child);
        }

        $this->children[$child] = false;

        return $this;
    }

    public function assert(bool $strict = true): void
    {
        parent::assert($strict);

        $errors = [];

        try {
            $this->errors?->assert($strict);
        } catch (TestFailedException $e) {
            $errors[] = new ExpectationFailedException(
                sprintf("%s\nerrors\n%s", $this->failureDescription(), $e->getMessage())
            );
        }

        $visited = [];
        $name    = $this->form->getName();

        if (!$this->childAssertion && !empty($this->children)) {
            throw new \RuntimeException('Cannot disable child assertions when declaring children expectations');
        }

        foreach ($this->children as $child => $test) {
            $hasChild = $this->form->has($child);
            if (false === $test) {
                if ($hasChild) {
                    $errors[] = new ExpectationFailedException(
                        sprintf('Failed asserting that "%s" child does not exist in "%s"', $child, $name)
                    );
                }
                continue;
            }
            if (!$hasChild) {
                $errors[] = new ExpectationFailedException(
                    sprintf('Failed asserting that "%s" child exists in "%s"', $child, $name)
                );
                continue;
            }

            try {
                $test->assert($strict);
            } catch (TestFailedException $e) {
                $errors[] = new ExpectationFailedException($e->getMessage());
            }
            $visited[] = $child;
        }

        if ($strict && $this->childAssertion && count($visited) !== $this->form->count()) {
            $names = array_map(static fn(FormInterface $form) => $form->getName(), iterator_to_array($this->form));
            $names = array_diff($names, $visited);

            $errors[] = new ExpectationFailedException(
                sprintf(
                    "%s\nFailed asserting that %s children does not exist.",
                    $this->failureDescription(),
                    implode(', ', $names)
                )
            );
        }

        if (!empty($errors)) {
            throw new TestFailedException($errors);
        }

        Assert::assertEmpty($errors);
    }

    protected function getValue(): FormInterface
    {
        return $this->form;
    }

    protected function failureDescription(): ?string
    {
        $form = $this->form;
        $name = $form->getName();
        while ($form = $form->getParent()) {
            $name = sprintf('%s.%s', $form->getName(), $name);
        }

        return sprintf('Expectation failed in form "%s"', $name);
    }
}
