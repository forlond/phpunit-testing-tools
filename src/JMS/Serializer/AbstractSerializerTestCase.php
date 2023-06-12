<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Accessor\DefaultAccessorStrategy;
use JMS\Serializer\Construction\UnserializeObjectConstructor;
use JMS\Serializer\GraphNavigator\DeserializationGraphNavigator;
use JMS\Serializer\GraphNavigator\SerializationGraphNavigator;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Metadata\Driver\AttributeDriver;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
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

    protected function createSerializationContext(?callable $configure): TestSerializationContext
    {
        $configurator = new TestSerializerConfigurator();
        $context      = new TestSerializationContext();
        $configurator
            ->setVisitorFactory('json', new JsonSerializationVisitorFactory())
            ->setVisitorFactory('xml', new XmlSerializationVisitorFactory())
        ;

        $configure && $configure($configurator, $context);

        $this->initializeContext($configurator, $context);

        return $context;
    }

    protected function createDeserializationContext(?callable $configure): TestDeserializationContext
    {
        $configurator = new TestSerializerConfigurator();
        $context      = new TestDeserializationContext();
        $configurator
            ->setVisitorFactory('json', new JsonDeserializationVisitorFactory())
            ->setVisitorFactory('xml', new XmlDeserializationVisitorFactory())
        ;

        $configure && $configure($configurator, $context);

        $this->initializeContext($configurator, $context);

        return $context;
    }

    private function initializeContext(
        TestSerializerConfigurator                          $configurator,
        TestSerializationContext|TestDeserializationContext $context,
    ): void {
        // Set default format
        $configurator->format = $configurator->format ?? 'json';

        // Metadata Factory
        if (null === ($metadataFactory = $configurator->metadataFactory)) {
            $metadataFactory = new MetadataFactory(
                new AttributeDriver(new IdenticalPropertyNamingStrategy()),
                ClassHierarchyMetadata::class,
                true
            );
        }
        $metadataFactory = new TestMetadataFactory($metadataFactory);

        // Graph Navigator
        if (null === ($navigator = $configurator->navigator)) {
            $navigator = match (get_class($context)) {
                TestSerializationContext::class   => new SerializationGraphNavigator(
                    $metadataFactory,
                    new HandlerRegistry(),
                    new DefaultAccessorStrategy(),
                ),
                TestDeserializationContext::class => new DeserializationGraphNavigator(
                    $metadataFactory,
                    new HandlerRegistry(),
                    new UnserializeObjectConstructor(),
                    new DefaultAccessorStrategy(),
                ),
            };
        }
        $navigator = new TestGraphNavigator($navigator);

        // Visitor
        if (null === ($visitor = $configurator->visitor)) {
            $visitor = $configurator->getVisitorFactory()->getVisitor();
        }
        $visitor = match (get_class($context)) {
            TestSerializationContext::class   => new TestSerializationVisitor($visitor),
            TestDeserializationContext::class => new TestDeserializationVisitor($visitor),
        };

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
