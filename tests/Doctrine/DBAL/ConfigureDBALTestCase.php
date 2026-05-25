<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\DBAL;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Forlond\TestTools\Doctrine\DBAL\AbstractDBALTestCase;
use Forlond\TestTools\Doctrine\DBAL\TestPlatform;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class ConfigureDBALTestCase extends AbstractDBALTestCase
{
    public function testConnectionWithoutParams(): void
    {
        $connection    = $this->createConnection();
        $configuration = $connection->getConfiguration();
        $platform      = $connection->getDatabasePlatform();

        self::assertSame([], $connection->getParams());
        self::assertInstanceOf(Configuration::class, $configuration);
        self::assertFalse($configuration->getAutoCommit());
        self::assertInstanceOf(MySQLPlatform::class, $platform);
        self::assertSame($platform, $connection->getDriver()->getDatabasePlatform());
    }

    public function testConnectionWithParams(): void
    {
        $configuration = new Configuration();
        $platform      = new TestPlatform();

        $connection = $this->createConnection($configuration, $platform);

        self::assertSame([], $connection->getParams());
        self::assertSame($configuration, $connection->getConfiguration());
        self::assertTrue($configuration->getAutoCommit());
        self::assertSame($platform, $connection->getDatabasePlatform());
        self::assertSame($platform, $connection->getDriver()->getDatabasePlatform());
    }

    public function createConfiguration(): Configuration
    {
        $configuration = parent::createConfiguration();
        $configuration->setAutoCommit(false);

        return $configuration;
    }

    public function createPlatform(): AbstractPlatform
    {
        return new MySQLPlatform();
    }
}
