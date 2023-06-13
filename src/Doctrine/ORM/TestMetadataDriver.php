<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\ORM;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestMetadataDriver implements MappingDriver
{
    public function __construct(
        public MappingDriver $delegate,
    ) {
    }

    public function loadMetadataForClass(string $className, ClassMetadata $metadata)
    {
        return $this->delegate->loadMetadataForClass($className, $metadata);
    }

    public function getAllClassNames()
    {
        return $this->delegate->getAllClassNames();
    }

    public function isTransient(string $className)
    {
        return $this->delegate->isTransient($className);
    }
}
