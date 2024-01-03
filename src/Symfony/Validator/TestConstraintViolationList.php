<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Validator;

use Forlond\TestTools\AbstractTestCollection;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Validator\Constraint as ValidatorConstraint;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestConstraintViolationList extends AbstractTestCollection
{
    public function __construct(
        private readonly ConstraintViolationListInterface $list,
    ) {
    }

    public function expect(Constraint|string $message): self
    {
        $this->next();
        $this->set(
            'message',
            $message,
            static fn(ConstraintViolationInterface $violation) => $violation->getMessage()
        );

        return $this;
    }

    public function path(Constraint|string $propertyPath): self
    {
        $this->set(
            'path',
            $propertyPath,
            static fn(ConstraintViolationInterface $violation) => $violation->getPropertyPath()
        );

        return $this;
    }

    public function parameters(Constraint|array $parameters): self
    {
        $this->set(
            'parameters',
            $parameters,
            static fn(ConstraintViolationInterface $violation) => $violation->getParameters()
        );

        return $this;
    }

    public function messageTemplate(Constraint|string $messageTemplate): self
    {
        $this->set(
            'messageTemplate',
            $messageTemplate,
            static fn(ConstraintViolationInterface $violation) => $violation->getMessageTemplate()
        );

        return $this;
    }

    public function invalidValue(mixed $invalidValue): self
    {
        $this->set(
            'invalidValue',
            $invalidValue,
            static fn(ConstraintViolationInterface $violation) => $violation->getInvalidValue()
        );

        return $this;
    }

    public function plural(Constraint|int $plural): self
    {
        $this->set(
            'plural',
            $plural,
            static fn(ConstraintViolationInterface $violation) => $violation->getPlural()
        );

        return $this;
    }

    public function constraint(Constraint|ValidatorConstraint $constraint): self
    {
        $this->set(
            'constraint',
            $constraint,
            static fn(ConstraintViolationInterface $violation) => $violation->getConstraint()
        );

        return $this;
    }

    public function code(Constraint|string $code): self
    {
        $this->set(
            'code',
            $code,
            static fn(ConstraintViolationInterface $violation) => $violation->getCode()
        );

        return $this;
    }

    public function root(mixed $root): self
    {
        $this->set(
            'root',
            $root,
            static fn(ConstraintViolationInterface $violation) => $violation->getRoot()
        );

        return $this;
    }

    protected function getCollection(): array
    {
        return iterator_to_array($this->list);
    }
}
