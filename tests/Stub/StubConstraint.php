<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Stub;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\ExpectationFailedException;

final class StubConstraint extends Constraint
{
    private mixed $other = null;

    public function __construct(
        public readonly bool   $matches,
        public readonly string $toString = 'test',
    ) {
    }

    public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        $this->other = $other;

        if ($returnResult) {
            return $this->matches;
        }

        if (!$this->matches) {
            throw new ExpectationFailedException(sprintf('%s %s', $this->toString, $description));
        }

        return null;
    }

    public function toString(): string
    {
        return $this->toString;
    }

    public function expect(mixed $other): void
    {
        if (!$other instanceof Constraint) {
            $other = new IsIdentical($other);
        }

        $other->evaluate($this->other);
    }
}
