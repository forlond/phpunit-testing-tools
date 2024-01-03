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
        public readonly mixed      $value,
    ) {
    }

    public function evaluate(mixed $other): bool
    {
        $value = $this->value;
        if (is_callable($value)) {
            $value = $value($other);
        }

        return $this->delegate->evaluate($value, $this->name, true);
    }
}
