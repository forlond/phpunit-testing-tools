<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Forlond\TestTools\Doctrine\DBAL\TestDBALDriver;
use Forlond\TestTools\Doctrine\DBAL\TestPlatform;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALDriverTest extends TestCase
{
    public function testConstructor(): void
    {
        $platform = new MySQLPlatform();
        $driver   = new TestDBALDriver($platform);

        self::assertSame($driver->platform, $platform);
    }

    public function testConnect(): void
    {
        $driver = $this->createDriver();

        $result = $driver->connect([]);

        self::assertSame($driver->connection, $result);
    }

    public function testGetDatabasePlatform(): void
    {
        $driver = $this->createDriver();

        $result = $driver->getDatabasePlatform();

        self::assertSame($driver->platform, $result);
    }

    public function testSchemaManager(): void
    {
        $driver     = $this->createDriver();
        $connection = self::createStub(Connection::class);

        $result = $driver->getSchemaManager($connection, $driver->platform);

        self::assertSame($driver->platform, $result->getDatabasePlatform());
    }

    public function testGetExceptionConverter(): void
    {
        $driver = $this->createDriver();

        $result = $driver->getExceptionConverter();

        self::assertSame($driver->exceptionConverter, $result);
    }

    public function createDriver(): TestDBALDriver
    {
        return new TestDBALDriver(new TestPlatform());
    }
}
