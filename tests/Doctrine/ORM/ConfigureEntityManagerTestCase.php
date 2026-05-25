<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\ORM;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Forlond\TestTools\Doctrine\DBAL\TestPlatform;
use Forlond\TestTools\Doctrine\ORM\AbstractEntityManagerTestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class ConfigureEntityManagerTestCase extends AbstractEntityManagerTestCase
{
    public function testEntityManagerWithoutParams(): void
    {
        $em = $this->createEntityManager();

        self::assertInstanceOf(MySqlPlatform::class, $em->getConnection()->getDatabasePlatform());
    }

    public function testEntityManagerWithParams(): void
    {
        $configuration = new Configuration();
        $configuration->setMetadataDriverImpl(new AttributeDriver([]));
        // @phpstan-ignore function.alreadyNarrowedType
        if (method_exists($configuration, 'enableNativeLazyObjects')) {
            $configuration->enableNativeLazyObjects(true);
        } else {
            $configuration->setProxyDir(sys_get_temp_dir());
            $configuration->setProxyNamespace('DoctrineTest');
        }

        $platform = new TestPlatform();

        $em = $this->createEntityManager($configuration, $platform);

        self::assertSame($configuration, $em->getConfiguration());
        self::assertSame($platform, $em->getConnection()->getDatabasePlatform());
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
