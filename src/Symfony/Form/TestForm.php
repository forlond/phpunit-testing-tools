<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Form;

use Forlond\TestTools\TestConstraintConfiguratorTrait;
use Forlond\TestTools\TestInterface;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Form\FormInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestForm implements TestInterface
{
    use TestConstraintConfiguratorTrait;

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

        $this->set('type', $value, $this->form->getConfig()->getType());

        return $this;
    }

    public function options(Constraint|array $value): self
    {
        $this->set('options', $value, $this->form->getConfig()->getOptions());

        return $this;
    }

    public function required(bool $value): self
    {
        $this->set('required', $value, $this->form->isRequired());

        return $this;
    }

    public function disabled(bool $value): self
    {
        $this->set('disabled', $value, $this->form->isDisabled());

        return $this;
    }

    public function isEmpty(bool $value): self
    {
        $this->set('isEmpty', $value, $this->form->isEmpty());

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
        $this->set('data', $value, $this->form->getData());

        return $this;
    }

    public function normData(mixed $value): self
    {
        $this->set('normData', $value, $this->form->getNormData());

        return $this;
    }

    public function viewData(mixed $value): self
    {
        $this->set('viewData', $value, $this->form->getViewData());

        return $this;
    }

    public function extraData(mixed $value): self
    {
        $this->set('extraData', $value, $this->form->getExtraData());

        return $this;
    }

    public function valid(bool $value): self
    {
        if (!$this->form->isSubmitted()) {
            throw new \RuntimeException('Cannot use valid() if the form was not submitted.');
        }

        $this->set('valid', $value, $this->form->isValid());

        return $this;
    }

    public function child(string $child, callable $expect): self
    {
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
        $this->children[$child] = false;

        return $this;
    }

    public function assert(bool $strict = true): void
    {
        $form = clone $this->form;
        $name = $form->getName();
        $this->evaluate($name);

        foreach ($this->children as $child => $test) {
            $hasChild = $form->has($child);
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
            $form->remove($child);
        }

        if ($strict && $form->count()) {
            $names = array_map(static fn(FormInterface $form) => $form->getName(), iterator_to_array($form));
            throw new ExpectationFailedException(
                sprintf('Failed asserting that "%s" children does not exist', implode(', ', $names))
            );
        }
    }
}
