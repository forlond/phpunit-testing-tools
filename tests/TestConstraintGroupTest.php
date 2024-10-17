<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests;

use Forlond\TestTools\TestConstraintGroup;
use Forlond\TestTools\TestConstraintInterface;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

final class TestConstraintGroupTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $group = new TestConstraintGroup([]);

        self::assertInstanceOf(TestConstraintInterface::class, $group);
    }

    public function testEvaluateEmptyConstraintsReturnResultTrue(): void
    {
        $group = new TestConstraintGroup([]);

        $result = $group->evaluate(null, true);

        self::assertTrue($result);
    }

    public function testEvaluateEmptyConstraintsReturnResultFalse(): void
    {
        $group = new TestConstraintGroup([]);

        $result = $group->evaluate(null);

        self::assertNull($result);
    }

    public function testEvaluateConstraintPassesReturnResultTrue(): void
    {
        $constraint = $this->createMock(TestConstraintInterface::class);

        $group = new TestConstraintGroup([$constraint]);

        $constraint
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
        ;

        $result = $group->evaluate(null, true);

        self::assertTrue($result);
    }

    public function testEvaluateConstraintPassesReturnResultFalse(): void
    {
        $constraint = $this->createMock(TestConstraintInterface::class);

        $group = new TestConstraintGroup([$constraint]);

        $constraint
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
        ;

        $result = $group->evaluate(null);

        self::assertNull($result);
    }

    public function testEvaluateConstraintFailsReturnResultTrue(): void
    {
        $constraint = $this->createMock(TestConstraintInterface::class);

        $group = new TestConstraintGroup([$constraint]);

        $constraint
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
            ->willThrowException(new ExpectationFailedException('Failure'))
        ;

        $result = $group->evaluate(null, true);

        self::assertFalse($result);
    }

    public function testEvaluateConstraintFailsReturnResultFalse(): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Failure');

        $constraint = $this->createMock(TestConstraintInterface::class);

        $group = new TestConstraintGroup([$constraint]);

        $constraint
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
            ->willThrowException(new ExpectationFailedException('Failure'))
        ;

        $group->evaluate(null);
    }

    public function testEvaluateConstraintsPassReturnResultTrue(): void
    {
        $constraint1 = $this->createMock(TestConstraintInterface::class);
        $constraint2 = $this->createMock(TestConstraintInterface::class);

        $group = new TestConstraintGroup([$constraint1, $constraint2]);

        $constraint1
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
        ;
        $constraint2
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
        ;

        $result = $group->evaluate(null, true);

        self::assertTrue($result);
    }

    public function testEvaluateConstraintsPassReturnResultFalse(): void
    {
        $constraint1 = $this->createMock(TestConstraintInterface::class);
        $constraint2 = $this->createMock(TestConstraintInterface::class);

        $group = new TestConstraintGroup([$constraint1, $constraint2]);

        $constraint1
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
        ;
        $constraint2
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
        ;

        $result = $group->evaluate(null);

        self::assertNull($result);
    }

    public function testEvaluateConstraintsFailReturnResultTrue(): void
    {
        $constraint1 = $this->createMock(TestConstraintInterface::class);
        $constraint2 = $this->createMock(TestConstraintInterface::class);

        $group = new TestConstraintGroup([$constraint1, $constraint2]);

        $constraint1
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
            ->willThrowException(new ExpectationFailedException('Failure 1'))
        ;
        $constraint2
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
            ->willThrowException(new ExpectationFailedException('Failure 2'))
        ;

        $result = $group->evaluate(null, true);

        self::assertFalse($result);
    }

    public function testEvaluateConstraintsFailReturnResultFalse(): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage("Failure 1\n\n\nFailure 2");

        $constraint1 = $this->createMock(TestConstraintInterface::class);
        $constraint2 = $this->createMock(TestConstraintInterface::class);

        $group = new TestConstraintGroup([$constraint1, $constraint2]);

        $constraint1
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
            ->willThrowException(new ExpectationFailedException('Failure 1'))
        ;
        $constraint2
            ->expects(self::once())
            ->method('evaluate')
            ->with(null, false)
            ->willThrowException(new ExpectationFailedException('Failure 2'))
        ;

        $group->evaluate(null);
    }

    public function testToStringEmptyConstraints(): void
    {
        $group = new TestConstraintGroup([]);

        $result = $group->toString();

        self::assertSame('', $result);
    }

    public function testToStringWithConstraint(): void
    {
        $constraint = $this->createMock(TestConstraintInterface::class);

        $group = new TestConstraintGroup([$constraint]);

        $constraint
            ->expects(self::once())
            ->method('toString')
            ->willReturn('test')
        ;

        $result = $group->toString();

        self::assertSame('test', $result);
    }

    public function testToStringWithConstraints(): void
    {
        $constraint1 = $this->createMock(TestConstraintInterface::class);
        $constraint2 = $this->createMock(TestConstraintInterface::class);

        $group = new TestConstraintGroup([$constraint1, $constraint2]);

        $constraint1
            ->expects(self::once())
            ->method('toString')
            ->willReturn('test 1')
        ;
        $constraint2
            ->expects(self::once())
            ->method('toString')
            ->willReturn('test 2')
        ;

        $result = $group->toString();

        self::assertSame("test 1\ntest 2", $result);
    }
}
