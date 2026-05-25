<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\DBAL;

use Forlond\TestTools\Doctrine\DBAL\TestResult;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestResultTest extends TestCase
{
    public function testFetchNumeric(): void
    {
        $result = new TestResult([['id' => 1, 'name' => 'John Doe']]);

        $value = $result->fetchNumeric();

        self::assertSame([1, 'John Doe'], $value);
    }

    public function testFetchNumericFalse(): void
    {
        $result = new TestResult([['id' => 1, 'name' => 'John Doe']]);

        $result->fetchNumeric();
        $value = $result->fetchNumeric();

        self::assertFalse($value);
    }

    public function testFetchAssociative(): void
    {
        $result = new TestResult([['id' => 1, 'name' => 'John Doe']]);

        $value = $result->fetchAssociative();

        self::assertSame(['id' => 1, 'name' => 'John Doe'], $value);
    }

    public function testFetchAssociativeFalse(): void
    {
        $result = new TestResult([['id' => 1, 'name' => 'John Doe']]);

        $result->fetchAssociative();
        $value = $result->fetchAssociative();

        self::assertFalse($value);
    }

    public function testFetchOne(): void
    {
        $result = new TestResult([['id' => 1, 'name' => 'John Doe']]);

        $value = $result->fetchOne();

        self::assertSame(1, $value);
    }

    public function testFetchOneFalse(): void
    {
        $result = new TestResult([['id' => 1, 'name' => 'John Doe']]);

        $result->fetchOne();
        $value = $result->fetchOne();

        self::assertFalse($value);
    }

    public function testFetchAllNumeric(): void
    {
        $result = new TestResult([
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ]);

        $value = $result->fetchAllNumeric();

        self::assertSame([[1, 'John Doe'], [2, 'Jane Doe']], $value);
    }

    public function testFetchAllAssociative(): void
    {
        $result = new TestResult([
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ]);

        $value = $result->fetchAllAssociative();

        self::assertSame([['id' => 1, 'name' => 'John Doe'], ['id' => 2, 'name' => 'Jane Doe']], $value);
    }

    public function testFetchFirstColumn(): void
    {
        $result = new TestResult([
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ]);

        $value = $result->fetchFirstColumn();

        self::assertSame([1, 2], $value);
    }

    public function testRowCount(): void
    {
        $result = new TestResult([
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ]);

        $value = $result->rowCount();

        self::assertSame(2, $value);
    }

    public function testColumnCount(): void
    {
        $result = new TestResult([
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ]);

        $value = $result->columnCount();

        self::assertSame(2, $value);
    }

    public function testFree(): void
    {
        $result = new TestResult([
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ]);

        $result->free();
        $value = $result->rowCount();

        self::assertSame(0, $value);
    }
}
