<?php declare(strict_types=1);

namespace Forlond\TestTools;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestConstraint implements TestConstraintInterface
{
    public function __construct(
        private readonly string     $name,
        private readonly Constraint $delegate,
        private readonly \Closure   $resolver,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function evaluate(mixed $other, bool $returnResult = false): ?bool
    {
        return $this->delegate->evaluate(($this->resolver)($other), $this->name, $returnResult);
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return sprintf('%s %s', $this->name, $this->delegate->toString());
    }
}
