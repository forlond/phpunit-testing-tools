<?php declare(strict_types=1);

namespace Forlond\TestTools;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * @internal
 */
final class TestConstraint
{
    public function __construct(
        private readonly Constraint $delegate,
        private readonly mixed      $value,
    ) {
    }

    public function evaluate(string $description = ''): ?bool
    {
        return $this->delegate->evaluate($this->value, $description);
    }
}
