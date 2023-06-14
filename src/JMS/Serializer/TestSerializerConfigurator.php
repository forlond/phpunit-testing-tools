<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigator\Factory\GraphNavigatorFactoryInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\Factory\DeserializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\SerializationVisitorFactory;
use Metadata\MetadataFactoryInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestSerializerConfigurator
{
    public string $format = 'json';

    public ?MetadataFactoryInterface $metadataFactory = null;

    private array $initialVisiting = [];

    /**
     * @var SerializationVisitorFactory[]|DeserializationVisitorFactory[]
     */
    private array $visitorFactories;

    public function __construct(
        public readonly SerializationContext|DeserializationContext $context,
        public GraphNavigatorFactoryInterface                       $graphNavigatorFactory,
    ) {
    }

    public function getVisitorFactory(): SerializationVisitorFactory|DeserializationVisitorFactory
    {
        return $this->visitorFactories[$this->format];
    }

    public function setVisitorFactory(
        string                                                    $format,
        SerializationVisitorFactory|DeserializationVisitorFactory $visitor,
    ): self {
        $this->visitorFactories[$format] = $visitor;

        return $this;
    }

    public function getInitialVisiting(): array
    {
        return $this->initialVisiting;
    }

    public function pushInitialVisiting(object $object, string $property): self
    {
        $this->initialVisiting[] = [$object, $property];

        return $this;
    }
}
