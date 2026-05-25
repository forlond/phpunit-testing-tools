<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Handler\SubscribingHandlerInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractSubscribingHandlerTestCase extends AbstractSerializerTestCase
{
    abstract protected function createHandler(?callable $configure): SubscribingHandlerInterface;
}
