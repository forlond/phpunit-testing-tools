<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\DBAL;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver\Exception\UnknownParameterType;
use Doctrine\DBAL\Driver\PDO\Exception as PDOException;
use Doctrine\DBAL\Exception\ConnectionLost;
use Forlond\TestTools\Doctrine\DBAL\AbstractDBALTestCase;
use Forlond\TestTools\Doctrine\DBAL\TestDBALDriver;
use Forlond\TestTools\Doctrine\DBAL\TestPlatform;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class DefaultDBALTestCase extends AbstractDBALTestCase
{
    public function testConnectionWithoutParams(): void
    {
        $connection    = $this->createConnection();
        $configuration = $connection->getConfiguration();
        $platform      = $connection->getDatabasePlatform();

        self::assertSame([], $connection->getParams());
        self::assertInstanceOf(Configuration::class, $configuration);
        self::assertInstanceOf(TestPlatform::class, $platform);
        self::assertSame($platform, $connection->getDriver()->getDatabasePlatform());
    }

    public function testConnectionWithParams(): void
    {
        $configuration = new Configuration();
        $platform      = new TestPlatform();

        $connection = $this->createConnection($configuration, $platform);

        self::assertSame([], $connection->getParams());
        self::assertSame($configuration, $connection->getConfiguration());
        self::assertSame($platform, $connection->getDatabasePlatform());
        self::assertSame($platform, $connection->getDriver()->getDatabasePlatform());
    }

    public function testChangeDatabaseName(): void
    {
        $connection = $this->createConnection();

        $connection->database = 'foobar';

        self::assertSame('foobar', $connection->getDatabase());
    }

    public function testSetResult(): void
    {
        $connection = $this->createConnection();

        $connection->setResult(
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Jane']
        );
        $value = $connection->fetchFirstColumn('SELECT * FROM foobar');

        self::assertSame([1, 2], $value);
    }

    public function testConfigureException(): void
    {
        $exception  = new ConnectionLost(new PDOException('Error'), null);
        $connection = $this->createConnection();
        $connection->setException($exception);

        $result = $connection->convertException($exception);

        self::assertSame($exception, $result);
    }

    public function testConfigureDriverException(): void
    {
        $exception  = new UnknownParameterType('param');
        $connection = $this->createConnection();
        $connection->setException($exception);

        $result = $connection->convertException($exception);

        self::assertNotSame($exception, $result);
        self::assertSame($exception, $result->getPrevious());
    }
}
