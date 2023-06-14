<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\DeserializationContext;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDeserializationContext extends DeserializationContext
{
    /**
     * Alias for same method in TestSerializationContext in order to have a common interface.
     */
    public function startVisiting(object $object)
    {
        $this->increaseDepth();
    }

    public function getMetadataFactory(): TestMetadataFactory
    {
        return parent::getMetadataFactory();
    }

    public function getVisitor(): TestDeserializationVisitor
    {
        return parent::getVisitor();
    }

    public function getNavigator(): TestGraphNavigator
    {
        return parent::getNavigator();
    }
}
