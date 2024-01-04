<?php declare(strict_types=1);

namespace Forlond\TestTools;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestConstraint implements TestConstraintInterface
{
    public function __construct(
        public readonly string     $name,
        public readonly Constraint $delegate,
        public readonly \Closure   $value,
    ) {
    }

    public function evaluate(mixed $other): bool
    {
        return $this->delegate->evaluate(($this->value)($other), $this->name, true);
    }
}
