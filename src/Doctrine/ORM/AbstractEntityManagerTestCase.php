<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Forlond\TestTools\Doctrine\DBAL\AbstractDBALTestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractEntityManagerTestCase extends AbstractDBALTestCase
{
    protected function createEntityManager(?callable $configure): TestEntityManager
    {
        $configuration = new TestConfiguration();

        $configure && $configure($configuration);

        // Apply necessary default values if the user has not set any.
        if (null === $configuration->platform) {
            throw new \RuntimeException('Configure a platform');
        }
        if (null === $configuration->getMetadataDriverImpl()) {
            $configuration->setMetadataDriverImpl(new AttributeDriver([]));
        }
        if (null === $configuration->getProxyDir()) {
            $configuration->setProxyDir(sys_get_temp_dir());
        }
        if (null === $configuration->getProxyNamespace()) {
            $configuration->setProxyNamespace('DoctrineTest');
        }

        return new TestEntityManager(
            new EntityManager($this->createConnection($configuration, $configuration->platform), $configuration)
        );
    }
}
