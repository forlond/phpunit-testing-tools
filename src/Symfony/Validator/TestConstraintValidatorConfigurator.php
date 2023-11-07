<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Validator;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\Constraint\IsIdentical;
use Symfony\Component\Validator\Constraint as ValidatorConstraint;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
class TestConstraintValidatorConfigurator
{
    private Constraint $message;

    private Constraint $path;

    private Constraint $parameters;

    private Constraint $messageTemplate;

    private Constraint $invalidValue;

    private Constraint $plural;

    private Constraint $constraint;

    public function __construct(
        Constraint|string                            $message,
        private readonly TestConstraintViolationList $list,
    ) {
        $this->message         = $this->normalize($message);
        $this->path            = new IsAnything();
        $this->parameters      = new IsAnything();
        $this->messageTemplate = new IsAnything();
        $this->invalidValue    = new IsAnything();
        $this->plural          = new IsAnything();
        $this->constraint      = new IsAnything();
    }

    public function expect(Constraint|string $message): self
    {
        return $this->list->expect($message);
    }

    public function path(Constraint|string $propertyPath): self
    {
        $this->path = $this->normalize($propertyPath);

        return $this;
    }

    public function parameters(Constraint|array $parameters): self
    {
        $this->parameters = $this->normalize($parameters);

        return $this;
    }

    public function messageTemplate(Constraint|string $messageTemplate): self
    {
        $this->messageTemplate = $this->normalize($messageTemplate);

        return $this;
    }

    public function invalidValue(mixed $invalidValue): self
    {
        $this->invalidValue = $this->normalize($invalidValue);

        return $this;
    }

    public function plural(Constraint|int $plural): self
    {
        $this->plural = $this->normalize($plural);

        return $this;
    }

    public function constraint(Constraint|ValidatorConstraint $constraint): self
    {
        $this->constraint = $this->normalize($constraint);

        return $this;
    }

    public function assert(bool $strict = true): void
    {
        $this->list->assert($strict);
    }

    public function matches(ConstraintViolationInterface $violation): bool
    {
        return $this->message->evaluate($violation->getMessage(), 'message', true) &&
            $this->path->evaluate($violation->getPropertyPath(), 'path', true) &&
            $this->parameters->evaluate($violation->getParameters(), 'parameters', true) &&
            $this->messageTemplate->evaluate($violation->getMessageTemplate(), 'messageTemplate', true) &&
            $this->invalidValue->evaluate($violation->getInvalidValue(), 'invalidValue', true) &&
            $this->plural->evaluate($violation->getPlural(), 'plural', true) &&
            $this->constraint->evaluate($violation->getConstraint(), 'constraint', true);
    }

    private function normalize(mixed $value): ?Constraint
    {
        return !$value instanceof Constraint ? new IsIdentical($value) : $value;
    }
}
