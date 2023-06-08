<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Visitor\Factory\DeserializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\SerializationVisitorFactory;
use JMS\Serializer\VisitorInterface;
use Metadata\MetadataFactoryInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestSerializerConfigurator
{
    public ?string $format = null;

    public ?VisitorInterface $visitor = null;

    public ?GraphNavigatorInterface $navigator = null;

    public ?MetadataFactoryInterface $metadataFactory = null;

    private array $initialVisiting = [];

    /**
     * @var SerializationVisitorFactory[]|DeserializationVisitorFactory[]
     */
    private array $visitorFactories;

    public function getVisitorFactory(): SerializationVisitorFactory|DeserializationVisitorFactory
    {
        if (null === $this->format) {
            throw new \RuntimeException('Set a format value first.');
        }

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
