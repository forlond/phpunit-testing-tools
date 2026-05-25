<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Construction\ObjectConstructorInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractObjectConstructorTestCase extends AbstractSerializerTestCase
{
    abstract protected function createConstructor(?callable $configure): ObjectConstructorInterface;
}
