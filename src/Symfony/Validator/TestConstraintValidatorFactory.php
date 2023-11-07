<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestConstraintValidatorFactory extends ConstraintValidatorFactory
{
    public function setInstance(string $name, ConstraintValidatorInterface $validator): self
    {
        $this->validators[$name] = $validator;

        return $this;
    }

    public function setNoopInstance(string $name): self
    {
        $validator = new class implements ConstraintValidatorInterface {
            public function initialize(ExecutionContextInterface $context)
            {
            }

            public function validate(mixed $value, Constraint $constraint)
            {
            }
        };
        $this->setInstance($name, $validator);

        return $this;
    }
}
