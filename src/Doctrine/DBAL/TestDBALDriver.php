<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALDriver implements Driver
{
    public readonly TestDBALDriverConnection $connection;

    public readonly TestExceptionConverter $exceptionConverter;

    public readonly AbstractPlatform $platform;

    public function __construct(AbstractPlatform $platform)
    {
        $this->connection         = new TestDBALDriverConnection();
        $this->exceptionConverter = new TestExceptionConverter();
        $this->platform           = $platform;
    }

    public function connect(array $params): TestDBALDriverConnection
    {
        return $this->connection;
    }

    public function getDatabasePlatform(): AbstractPlatform
    {
        return $this->platform;
    }

    public function getSchemaManager(Connection $conn, AbstractPlatform $platform): TestSchemaManager
    {
        return new TestSchemaManager($conn, $platform);
    }

    /**
     * @inheritDoc
     */
    public function getExceptionConverter(): TestExceptionConverter
    {
        return $this->exceptionConverter;
    }
}
