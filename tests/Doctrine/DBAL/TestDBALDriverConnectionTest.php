<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\DBAL;

use Forlond\TestTools\Doctrine\DBAL\TestDBALDriverConnection;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALDriverConnectionTest extends TestCase
{
    public function testResults(): void
    {
        $connection = $this->createDriverConnection();

        self::assertSame([], $connection->results);
    }

    public function testPrepare(): void
    {
        $connection = $this->createDriverConnection();

        $statement = $connection->prepare('SELECT * FROM test');

        self::assertSame([], $statement->results);
        self::assertSame([], $statement->params);
        self::assertSame([], $statement->types);
        self::assertSame('SELECT * FROM test', $statement->sql);
    }

    public function testQuery(): void
    {
        $connection = $this->createDriverConnection();

        $result = $connection->query('SELECT * FROM test');

        self::assertSame(0, $result->rowCount());
        self::assertSame('SELECT * FROM test', $result->sql);
    }

    public function testQuote(): void
    {
        $connection = $this->createDriverConnection();

        $result = $connection->quote('value');

        self::assertSame('value', $result);
    }

    public function testExec(): void
    {
        $connection = $this->createDriverConnection();

        $result = $connection->exec('SELECT * FROM test');

        self::assertSame(1, $result);
    }

    public function testBeginTransaction(): void
    {
        $connection = $this->createDriverConnection();

        $result = $connection->beginTransaction();

        self::assertTrue($result);
    }

    public function testCommit(): void
    {
        $connection = $this->createDriverConnection();

        $result = $connection->commit();

        self::assertTrue($result);
    }

    public function testRollBack(): void
    {
        $connection = $this->createDriverConnection();

        $result = $connection->rollBack();

        self::assertTrue($result);
    }

    public function testGetNativeConnection(): void
    {
        $connection = $this->createDriverConnection();

        $result = $connection->getNativeConnection();

        self::assertSame($connection, $result);
    }

    private function createDriverConnection(): TestDBALDriverConnection
    {
        return new TestDBALDriverConnection();
    }
}
