<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\Factory\DeserializationVisitorFactory;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDeserializationVisitorFactory implements DeserializationVisitorFactory
{
    public function __construct(
        private readonly \Closure $closure,
    ) {
    }

    public function getVisitor(): DeserializationVisitorInterface
    {
        return ($this->closure)();
    }
}
