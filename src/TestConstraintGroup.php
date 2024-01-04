<?php declare(strict_types=1);

namespace Forlond\TestTools;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestConstraintGroup implements TestConstraintInterface
{
    public function __construct(
        /**
         * @var array<TestConstraintInterface>
         */
        private readonly array $constraints,
    ) {
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
