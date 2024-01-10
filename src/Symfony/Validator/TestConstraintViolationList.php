<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Validator;

use Forlond\TestTools\AbstractTestGroup;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestConstraintViolationList extends AbstractTestGroup
{
    protected const GROUP_NAME = 'violation list';

    public function __construct(
        private readonly ConstraintViolationListInterface $list,
    ) {
    }

    public function expect(Constraint|string $message): self
    {
        $this->next();
        $this->set('message', $message, static fn(ConstraintViolationInterface $violation) => $violation->getMessage());

        return $this;
    }

    public function path(Constraint|string $value): self
    {
        $this->set('path', $value, static fn(ConstraintViolationInterface $violation) => $violation->getPropertyPath());

        return $this;
    }

    public function parameters(Constraint|array $value): self
    {
        $this->set(
            'parameters',
            $value,
            static fn(ConstraintViolationInterface $violation) => $violation->getParameters()
        );

        return $this;
    }

    public function messageTemplate(Constraint|string $value): self
    {
        $this->set(
            'messageTemplate',
            $value,
            static fn(ConstraintViolationInterface $violation) => $violation->getMessageTemplate()
        );

        return $this;
    }

    public function invalidValue(mixed $value): self
    {
        $this->set(
            'invalidValue',
            $value,
            static fn(ConstraintViolationInterface $violation) => $violation->getInvalidValue()
        );

        return $this;
    }

    public function plural(Constraint|int $value): self
    {
        $this->set('plural', $value, static fn(ConstraintViolationInterface $violation) => $violation->getPlural());

        return $this;
    }

    public function constraint(Constraint|string $value): self
    {
        if (is_string($value)) {
            $value = new IsInstanceOf($value);
        }

        $this->set(
            'constraint',
            $value,
            static fn(ConstraintViolationInterface $violation) => $violation->getConstraint()
        );

        return $this;
    }

    public function code(Constraint|string $value): self
    {
        $this->set('code', $value, static fn(ConstraintViolationInterface $violation) => $violation->getCode());

        return $this;
    }

    public function root(mixed $value): self
    {
        $this->set('root', $value, static fn(ConstraintViolationInterface $violation) => $violation->getRoot());

        return $this;
    }

    protected function getValue(): array
    {
        return iterator_to_array($this->list);
    }
}
