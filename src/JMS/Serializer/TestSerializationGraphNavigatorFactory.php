<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Accessor\AccessorStrategyInterface;
use JMS\Serializer\Accessor\DefaultAccessorStrategy;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use JMS\Serializer\Expression\ExpressionEvaluatorInterface;
use JMS\Serializer\GraphNavigator\Factory\GraphNavigatorFactoryInterface;
use JMS\Serializer\GraphNavigator\SerializationGraphNavigator;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use Metadata\MetadataFactoryInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestSerializationGraphNavigatorFactory implements GraphNavigatorFactoryInterface
{
    public HandlerRegistryInterface $handlerRegistry;

    public AccessorStrategyInterface $accessor;

    public ?EventDispatcherInterface $dispatcher = null;

    public ?ExpressionEvaluatorInterface $expressionEvaluator = null;

    /**
     * @internal
     */
    public MetadataFactoryInterface $metadataFactory;

    public function __construct()
    {
        $this->handlerRegistry = new HandlerRegistry();
        $this->accessor        = new DefaultAccessorStrategy();
    }

    public function getGraphNavigator(): GraphNavigatorInterface
    {
        if (!isset($this->metadataFactory)) {
            throw new \RuntimeException('AbstractSerializerTestCase must set a metadata factory.');
        }

        return new SerializationGraphNavigator(
            $this->metadataFactory,
            $this->handlerRegistry,
            $this->accessor,
            $this->dispatcher,
            $this->expressionEvaluator
        );
    }
}
