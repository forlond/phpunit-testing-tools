<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Visitor\Factory\SerializationVisitorFactory;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestSerializationVisitorFactory implements SerializationVisitorFactory
{
    public function __construct(
        private readonly \Closure $closure,
    ) {
    }

    public function getVisitor(): SerializationVisitorInterface
    {
        return ($this->closure)();
    }
}
