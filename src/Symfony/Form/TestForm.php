<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Form;

use Forlond\TestTools\AbstractTest;
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

    public function __construct(
        private readonly FormInterface $form,
    ) {
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

    public function path(): self
    {
        throw new \RuntimeException('Not implemented yet.');
    }

    public function errors(): self
    {
        throw new \RuntimeException('Not implemented yet.');
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

    public function child(string $child, callable $expect): self
    {
        if (isset($this->children[$child])) {
            throw new \RuntimeException('Cannot redefine child' . $child);
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
            throw new \RuntimeException('Cannot redefine child' . $child);
        }

        $this->children[$child] = false;

        return $this;
    }

    public function assert(bool $strict = true): void
    {
        parent::assert($strict);

        $name    = $this->form->getName();
        $visited = [];

        foreach ($this->children as $child => $test) {
            $hasChild = $this->form->has($child);
            if (false === $test) {
                if ($hasChild) {
                    throw new ExpectationFailedException(
                        sprintf('Failed asserting that "%s" child does not exist in "%s"', $child, $name)
                    );
                }
                continue;
            }
            if (!$hasChild) {
                throw new ExpectationFailedException(
                    sprintf('Failed asserting that "%s" child exists in "%s"', $child, $name)
                );
            }

            $test->assert($strict);
            $visited[] = $child;
        }

        if ($strict && count($visited) !== $this->form->count()) {
            $names = array_map(static fn(FormInterface $form) => $form->getName(), iterator_to_array($this->form));
            $names = array_diff($names, $visited);

            throw new ExpectationFailedException(
                sprintf('Failed asserting that "%s" children does not exist', implode(', ', $names))
            );
        }
    }

    protected function getValue(): FormInterface
    {
        return $this->form;
    }
}
