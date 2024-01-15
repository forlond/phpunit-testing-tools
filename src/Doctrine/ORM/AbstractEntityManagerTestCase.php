<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\ORM;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Forlond\TestTools\Doctrine\DBAL\AbstractDBALTestCase;
use Forlond\TestTools\Doctrine\DBAL\TestPlatform;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractEntityManagerTestCase extends AbstractDBALTestCase
{
    protected function createEntityManager(?callable $configure, ?AbstractPlatform $platform = null): TestEntityManager
    {
        $configuration = new Configuration();
        $configure && $configure($configuration);

        // Apply necessary default values if the user has not set any.
        $configuration->setMetadataDriverImpl($configuration->getMetadataDriverImpl() ?? new AttributeDriver([]));
        $configuration->setProxyDir($configuration->getProxyDir() ?? sys_get_temp_dir());
        $configuration->setProxyNamespace($configuration->getProxyNamespace() ?? 'DoctrineTest');

        return new TestEntityManager(
            new EntityManager($this->createConnection($platform ?? $this->getPlatform()), $configuration)
        );
    }

    protected function getPlatform(): AbstractPlatform
    {
        return new TestPlatform();
    }
}
