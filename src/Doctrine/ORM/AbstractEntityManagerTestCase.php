<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\ORM;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Forlond\TestTools\Doctrine\DBAL\AbstractDBALTestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractEntityManagerTestCase extends AbstractDBALTestCase
{
    final protected function createEntityManager(
        ?Configuration    $configuration = null,
        ?AbstractPlatform $platform = null,
    ): TestEntityManager {
        $configuration = $configuration ?? $this->createConfiguration();
        $connection    = $this->createConnection($configuration, $platform);

        return new TestEntityManager(new EntityManager($connection, $configuration));
    }

    protected function createConfiguration(): Configuration
    {
        $configuration = new Configuration();
        // Apply default values, the user can modify this.
        $configuration->setMetadataDriverImpl(new AttributeDriver([]));
        $configuration->setProxyDir(sys_get_temp_dir());
        $configuration->setProxyNamespace('DoctrineTest');

        return $configuration;
    }
}
