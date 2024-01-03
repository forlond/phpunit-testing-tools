<?php declare(strict_types=1);

namespace Forlond\TestTools;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestConstraintCollection implements TestConstraintInterface
{
    /**
     * @var array<TestConstraintInterface>
     */
    private array $constraints = [];

    public function addConstraint(TestConstraintInterface $constraint): self
    {
        $this->constraints[] = $constraint;

        return $this;
    }

    public function evaluate(mixed $other): bool
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint->evaluate($other)) {
                return false;
            }
        }

        return true;
    }
}
