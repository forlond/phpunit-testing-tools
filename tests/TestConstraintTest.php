<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests;

use Forlond\TestTools\TestConstraint;
use Forlond\TestTools\TestConstraintInterface;
use Forlond\TestTools\Tests\Stub\StubConstraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

final class TestConstraintTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $delegate   = new StubConstraint(true);
        $resolver   = static fn(mixed $value) => $value * 2;
        $constraint = new TestConstraint('name', $delegate, $resolver);

        self::assertInstanceOf(TestConstraintInterface::class, $constraint);
    }

    public function testEvaluateDelegatePassesReturnValueTrue(): void
    {
        $delegate   = new StubConstraint(true);
        $resolver   = static fn(mixed $value) => $value * 2;
        $constraint = new TestConstraint('name', $delegate, $resolver);

        $result = $constraint->evaluate(10, true);

        $delegate->expect(20);
        self::assertTrue($result);
    }

    public function testEvaluateDelegatePassesReturnValueFalse(): void
    {
        $delegate   = new StubConstraint(true);
        $resolver   = static fn(mixed $value) => $value * 2;
        $constraint = new TestConstraint('name', $delegate, $resolver);

        $result = $constraint->evaluate(10);

        $delegate->expect(20);
        self::assertNull($result);
    }

    public function testEvaluateDelegateFailsReturnValueTrue(): void
    {
        $delegate   = new StubConstraint(false);
        $resolver   = static fn(mixed $value) => $value * 2;
        $constraint = new TestConstraint('name', $delegate, $resolver);

        $result = $constraint->evaluate(10, true);

        $delegate->expect(20);
        self::assertFalse($result);
    }

    public function testEvaluateDelegateFailsReturnValueFalse(): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('test name');

        $delegate   = new StubConstraint(false);
        $resolver   = static fn(mixed $value) => $value * 2;
        $constraint = new TestConstraint('name', $delegate, $resolver);

        try {
            $constraint->evaluate(10);
        } finally {
            $delegate->expect(20);
        }
    }
}
