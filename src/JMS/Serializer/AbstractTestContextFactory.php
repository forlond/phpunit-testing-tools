<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator\Factory\GraphNavigatorFactoryInterface;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\Driver\AttributeDriver;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Metadata\ClassHierarchyMetadata;
use Metadata\MetadataFactory;
use Metadata\MetadataFactoryInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractTestContextFactory
{
    public string $format = 'json';

    public MetadataFactoryInterface $metadataFactory;

    protected array $initialGraph = [];

    public function __construct(
        public GraphNavigatorFactoryInterface $graphNavigatorFactory,
    ) {
        $this->metadataFactory = new MetadataFactory(
            new AttributeDriver(new IdenticalPropertyNamingStrategy()),
            ClassHierarchyMetadata::class,
            true
        );
    }

    final public function pushInitialGraph(object $object, string $property): self
    {
        $this->initialGraph[] = [$object, $property];

        return $this;
    }

    abstract protected function startInitialVisiting(Context $context, object $object): void;

    abstract protected function getVisitor(): SerializationVisitorInterface|DeserializationVisitorInterface;

    protected function getNavigator(): GraphNavigatorInterface
    {
        return $this->graphNavigatorFactory->getGraphNavigator();
    }

    final protected function createContext(Context $context): void
    {
        $visitor   = $this->getVisitor();
        $navigator = $this->getNavigator();

        $context->initialize($this->format, $visitor, $navigator, $this->metadataFactory);
        $visitor->setNavigator($navigator);
        $navigator->initialize($visitor, $context);

        $this->buildInitialGraph($context, $visitor);
    }

    private function buildInitialGraph(
        Context                                                       $context,
        SerializationVisitorInterface|DeserializationVisitorInterface $visitor,
    ): void {
        foreach ($this->initialGraph as [$object, $propertyName]) {
            $metadata = $this->metadataFactory->getMetadataForClass($object::class);
            if (!$metadata instanceof ClassMetadata) {
                throw new \RuntimeException('Cannot get valid metadata for class: ' . $object::class);
            }

            $this->startInitialVisiting($context, $object);
            $context->pushClassMetadata($metadata);
            $property = $metadata->propertyMetadata[$propertyName] ?? null;
            if (null === $property) {
                throw new \RuntimeException('Cannot find property in class metadata');
            }

            $visitor->startVisitingObject($metadata, $object, ['name' => $metadata->name, 'params' => []]);
            $context->pushPropertyMetadata($property);
        }
    }
}
