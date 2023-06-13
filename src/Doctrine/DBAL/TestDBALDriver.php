<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALDriver implements Driver
{
    public function __construct(
        public readonly TestDBALDriverConnection $connection,
        public readonly TestExceptionConverter   $exceptionConverter,
        private readonly AbstractPlatform        $platform,
        private readonly ?Driver                 $delegate = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        if ($this->delegate) {
            return $this->delegate->connect($params, $username, $password, $driverOptions);
        }

        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    public function getDatabasePlatform()
    {
        if ($this->delegate) {
            return $this->delegate->getDatabasePlatform();
        }

        return $this->platform;
    }

    /**
     * @inheritDoc
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform)
    {
        if ($this->delegate) {
            return $this->delegate->getSchemaManager($conn, $platform);
        }

        return new TestSchemaManager($conn, $platform);
    }

    /**
     * @inheritDoc
     */
    public function getExceptionConverter(): ExceptionConverter
    {
        if ($this->delegate) {
            return $this->delegate->getExceptionConverter();
        }

        return $this->exceptionConverter;
    }
}
