<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests;

use Forlond\TestTools\PHPUnit\Constraint\WithConsecutive;
use Forlond\TestTools\TestConstraintGroup;
use Forlond\TestTools\TestConstraintInterface;
use PHPUnit\Framework\TestCase;

final class TestFile extends TestCase
{
    public function testFile(): void
    {
        $m = $this->createMock(TestConstraintInterface::class);
        $e = new TestConstraintGroup([$m, $m]);

        // mixed $other, bool $returnResult = false
        $m
            ->expects($this->exactly(2))
            ->method('evaluate')
            ->with(...WithConsecutive::from([1, false], [self::lessThan(2), false]))
        ;

        $e->evaluate(1);

    }
}
