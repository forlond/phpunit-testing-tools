<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\DBAL;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver\Exception\UnknownParameterType;
use Doctrine\DBAL\ParameterType;
use Forlond\TestTools\Doctrine\DBAL\TestDBALConnection;
use Forlond\TestTools\Doctrine\DBAL\TestDBALDriver;
use Forlond\TestTools\Doctrine\DBAL\TestPlatform;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALConnectionTest extends TestCase
{
    public function testConstructor(): void
    {
        $connection = $this->createConnection();

        self::assertSame([], $connection->getParams());
        self::assertNotNull($connection->getEventManager());
        self::assertTrue($connection->isAutoCommit());
    }

    public function testCreateExpressionBuilder(): void
    {
        $connection = $this->createConnection();
        $builder    = $connection->createExpressionBuilder();

        $result = $builder->literal('test', ParameterType::STRING);

        self::assertSame('test', $result);
    }

    public function testFetchAssociative(): void
    {
        $connection = $this->createConnection();
        $connection->setResult(['id' => 1, 'name' => 'John']);

        $result = $connection->fetchAssociative('SELECT * FROM test WHERE id = ?', [1], [ParameterType::INTEGER]);

        self::assertSame(['id' => 1, 'name' => 'John'], $result);
    }

    public function testFetchNumeric(): void
    {
        $connection = $this->createConnection();
        $connection->setResult(['id' => 1, 'name' => 'John']);

        $result = $connection->fetchNumeric('SELECT * FROM test WHERE id = ?', [1], [ParameterType::INTEGER]);

        self::assertSame([1, 'John'], $result);
    }

    public function testFetchOne(): void
    {
        $connection = $this->createConnection();
        $connection->setResult(['id' => 1, 'name' => 'John']);

        $result = $connection->fetchOne('SELECT * FROM test WHERE id = ?', [1], [ParameterType::INTEGER]);

        self::assertSame(1, $result);
    }

    public function testFetchAllNumeric(): void
    {
        $connection = $this->createConnection();
        $connection->setResult(['id' => 1, 'name' => 'John']);

        $result = $connection->fetchAllNumeric('SELECT * FROM test WHERE id = ?', [1], [ParameterType::INTEGER]);

        self::assertSame([[1, 'John']], $result);
    }

    public function testFetchAllAssociative(): void
    {
        $connection = $this->createConnection();
        $connection->setResult(['id' => 1, 'name' => 'John']);

        $result = $connection->fetchAllAssociative('SELECT * FROM test WHERE id = ?', [1], [ParameterType::INTEGER]);

        self::assertSame([['id' => 1, 'name' => 'John']], $result);
    }

    public function testFetchAllKeyValue(): void
    {
        $connection = $this->createConnection();
        $connection->setResult(['id' => 1, 'name' => 'John']);

        $result = $connection->fetchAllKeyValue('SELECT * FROM test WHERE id = ?', [1], [ParameterType::INTEGER]);

        self::assertSame([1 => 'John'], $result);
    }

    public function testFetchAllAssociativeIndexed(): void
    {
        $connection = $this->createConnection();
        $connection->setResult(['id' => 1, 'name' => 'John']);

        $result = $connection->fetchAllAssociativeIndexed(
            'SELECT * FROM test WHERE id = ?',
            [1],
            [ParameterType::INTEGER]
        );

        self::assertSame([1 => ['name' => 'John']], $result);
    }

    public function testFetchFirstColumn(): void
    {
        $connection = $this->createConnection();
        $connection->setResult(['id' => 1, 'name' => 'John']);

        $result = $connection->fetchFirstColumn('SELECT * FROM test WHERE id = ?', [1], [ParameterType::INTEGER]);

        self::assertSame([1], $result);
    }

    public function testExecuteQuery(): void
    {
        $connection = $this->createConnection();
        $connection->setResult(['id' => 1, 'name' => 'John']);

        $result = $connection->executeQuery('DELETE FROM test WHERE id = ?', [1], [ParameterType::INTEGER]);

        self::assertSame(1, $result->rowCount());
    }

    public function testExecuteStatement(): void
    {
        $connection = $this->createConnection();
        $connection->setResult(['id' => 1, 'name' => 'John']);

        $result = $connection->executeStatement('DELETE FROM test WHERE id = ?', [1], [ParameterType::INTEGER]);

        self::assertSame(1, $result);
    }

    public function testConvertException(): void
    {
        $connection = $this->createConnection();
        $exception  = new UnknownParameterType('Error');

        $connection->getDriver()->exceptionConverter->exception =

        $result = $connection->convertException($exception);

        self::assertNotSame($exception, $result);
    }

    public function testGetDatabase(): void
    {
        $connection = $this->createConnection();

        self::assertSame('test_database', $connection->getDatabase());
    }

    public function testSetResult(): void
    {
        $connection = $this->createConnection();
        $results    = [
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Jane'],
        ];

        $connection->setResult(...$results);

        self::assertSame($results, $connection->getDriver()->connection->results);
    }

    private function createConnection(): TestDBALConnection
    {
        return new TestDBALConnection(new TestDBALDriver(new TestPlatform()), new Configuration());
    }
}
