<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator\Factory\GraphNavigatorFactoryInterface;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Metadata\Driver\AttributeDriver;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Visitor\Factory\DeserializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\SerializationVisitorFactory;
use JMS\Serializer\VisitorInterface;
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

    /**
     * @var SerializationVisitorFactory[]|DeserializationVisitorFactory[]
     */
    protected array $visitorFactories = [];

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

    abstract protected function getContext(): Context;

    protected function getNavigator(): GraphNavigatorInterface
    {
        return $this->graphNavigatorFactory->getGraphNavigator();
    }

    final protected function createContext(): Context
    {
        $context   = clone $this->getContext();
        $visitor   = $this->getVisitor()->getVisitor();
        $navigator = $this->getNavigator();

        $context->initialize($this->format, $visitor, $navigator, $this->metadataFactory);
        $visitor->setNavigator($navigator);
        $navigator->initialize($visitor, $context);

        $this->buildInitialGraph($context, $visitor);

        return $context;
    }

    private function getVisitor(): SerializationVisitorFactory|DeserializationVisitorFactory
    {
        return $this->visitorFactories[$this->format];
    }

    private function buildInitialGraph(Context $context, VisitorInterface $visitor): void
    {
        foreach ($this->initialGraph as [$object, $propertyName]) {
            $metadata = $this->metadataFactory->getMetadataForClass(get_class($object));
            $this->startInitialVisiting($context, $object);
            $context->pushClassMetadata($metadata);
            $property = $metadata->propertyMetadata[$propertyName] ?? null;
            if (null === $property) {
                throw new \RuntimeException('Cannot find property in class metadata');
            }

            $visitor->startVisitingObject($metadata, $object, ['name' => $metadata->name]);
            $context->pushPropertyMetadata($property);
        }
    }
}
