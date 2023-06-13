<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\ORM;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Configuration;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
class TestConfiguration extends Configuration
{
    public ?AbstractPlatform $platform = null;

    public function setMetadataDriverImpl(MappingDriver $driverImpl): void
    {
        parent::setMetadataDriverImpl(new TestMetadataDriver($driverImpl));
    }
}
