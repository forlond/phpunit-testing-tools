<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Metadata\Driver\AttributeDriver;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Visitor\Factory\JsonDeserializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\JsonSerializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\XmlDeserializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\XmlSerializationVisitorFactory;
use Metadata\ClassHierarchyMetadata;
use Metadata\MetadataFactory;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractSerializerTestCase extends TestCase
{
    protected function createSerializer(?callable $configure): Serializer
    {
        $builder = new SerializerBuilder();

        if ($configure) {
            $configure($builder);
        } else {
            $builder
                ->addDefaultHandlers()
                ->addDefaultListeners()
                ->addDefaultSerializationVisitors()
                ->addDefaultDeserializationVisitors()
            ;
        }

        return $builder->build();
    }

    protected function createSerializationContext(?callable $configure): SerializationContext
    {
        $context      = new SerializationContext();
        $configurator = new TestSerializerConfigurator($context, new TestSerializationGraphNavigatorFactory());
        $configurator
            ->setVisitorFactory('json', new JsonSerializationVisitorFactory())
            ->setVisitorFactory('xml', new XmlSerializationVisitorFactory())
        ;

        $configure && $configure($configurator);

        $this->initializeContext($configurator);

        return $context;
    }

    protected function createDeserializationContext(?callable $configure): DeserializationContext
    {
        $context      = new DeserializationContext();
        $configurator = new TestSerializerConfigurator($context, new TestDeserializationGraphNavigatorFactory());
        $configurator
            ->setVisitorFactory('json', new JsonDeserializationVisitorFactory())
            ->setVisitorFactory('xml', new XmlDeserializationVisitorFactory())
        ;

        $configure && $configure($configurator);

        $this->initializeContext($configurator);

        return $context;
    }

    private function initializeContext(TestSerializerConfigurator $configurator): void
    {
        $context = $configurator->context;

        // Metadata Factory
        if (null === ($metadataFactory = $configurator->metadataFactory)) {
            $metadataFactory = new MetadataFactory(
                new AttributeDriver(new IdenticalPropertyNamingStrategy()),
                ClassHierarchyMetadata::class,
                true
            );
        }

        // Graph Navigator
        $factory = $configurator->graphNavigatorFactory;
        if ($factory instanceof TestSerializationGraphNavigatorFactory ||
            $factory instanceof TestDeserializationGraphNavigatorFactory
        ) {
            $factory->metadataFactory = $metadataFactory;
        }
        $navigator = $factory->getGraphNavigator();

        // Visitor
        $visitor = $configurator->getVisitorFactory()->getVisitor();

        $context->initialize(
            $configurator->format,
            $visitor,
            $navigator,
            $metadataFactory,
        );
        $visitor->setNavigator($navigator);
        $navigator->initialize($visitor, $context);

        // Add initial visiting objects
        foreach ($configurator->getInitialVisiting() as [$object, $propertyName]) {
            $metadata = $metadataFactory->getMetadataForClass(get_class($object));
            $context->startVisiting($object);
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
