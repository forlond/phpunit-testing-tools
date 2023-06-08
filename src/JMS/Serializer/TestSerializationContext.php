<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestSerializationContext extends SerializationContext
{
    public function getVisitor(): SerializationVisitorInterface
    {
        return parent::getVisitor();
    }
}
