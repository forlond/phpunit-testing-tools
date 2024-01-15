<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Query;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\StringType;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALDriver implements Driver
{
    public function __construct(
        public readonly TestDBALDriverConnection $connection,
        private readonly AbstractPlatform        $platform,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function connect(
        array $params,
              $username = null,
              $password = null,
        array $driverOptions = [],
    ): TestDBALDriverConnection {
        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    public function getDatabasePlatform()
    {
        return $this->platform;
    }

    /**
     * @inheritDoc
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform)
    {
        return new class() extends AbstractSchemaManager {
            protected function _getPortableTableColumnDefinition($tableColumn)
            {
                return new Column('test_column', new StringType());
            }
        };
    }

    /**
     * @inheritDoc
     */
    public function getExceptionConverter(): ExceptionConverter
    {
        return new class() implements ExceptionConverter {
            public function convert(Exception $exception, ?Query $query): DriverException
            {
                return new DriverException($exception, $query);
            }
        };
    }
}
