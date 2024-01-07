<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\GraphNavigator\Factory\GraphNavigatorFactoryInterface;
use JMS\Serializer\GraphNavigatorInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestGraphNavigatorFactory implements GraphNavigatorFactoryInterface
{
    public function __construct(
        private readonly \Closure $closure,
    ) {
    }

    public function getGraphNavigator(): GraphNavigatorInterface
    {
        return ($this->closure)();
    }
}
