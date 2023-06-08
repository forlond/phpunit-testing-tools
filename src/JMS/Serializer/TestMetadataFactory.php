<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use Metadata\AdvancedMetadataFactoryInterface;
use Metadata\MetadataFactoryInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestMetadataFactory implements MetadataFactoryInterface, AdvancedMetadataFactoryInterface
{
    public function __construct(
        private readonly MetadataFactoryInterface $delegate,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getMetadataForClass(string $className)
    {
        return $this->delegate->getMetadataForClass($className);
    }

    /**
     * @inheritDoc
     */
    public function getAllClassNames(): array
    {
        if ($this->delegate instanceof AdvancedMetadataFactoryInterface) {
            return $this->delegate->getAllClassNames();
        }

        return [];
    }
}
