<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\SerializationContext;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestSerializationContext extends SerializationContext
{
    public function getMetadataFactory(): TestMetadataFactory
    {
        return parent::getMetadataFactory();
    }

    public function getVisitor(): TestSerializationVisitor
    {
        return parent::getVisitor();
    }

    public function getNavigator(): TestGraphNavigator
    {
        return parent::getNavigator();
    }
}
