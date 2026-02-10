<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\EventDispatcher\Event;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Type\Type;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 *
 * @phpstan-import-type TypeArray from Type
 */
abstract class AbstractEventSubscriberTestCase extends AbstractSerializerTestCase
{
    abstract protected function createSubscriber(?\Closure $configure): EventSubscriberInterface;

    protected function createPreSerializeEvent(
        SerializationContext $context,
        object               $object,
        ?string              $type = null,
    ): PreSerializeEvent {
        $context->startVisiting($object);

        return new PreSerializeEvent($context, $object, $this->parseType($type ?? $object::class));
    }

    protected function createPreDeserializeEvent(
        DeserializationContext $context,
        mixed                  $data,
        string                 $type,
    ): PreDeserializeEvent {
        $context->increaseDepth();

        return new PreDeserializeEvent($context, $data, $this->parseType($type));
    }

    protected function createPostSerializeEvent(
        SerializationContext $context,
        object               $object,
        ?string              $type = null,
    ): ObjectEvent {
        return new ObjectEvent($context, $object, $this->parseType($type ?? $object::class));
    }

    protected function createPostDeserializeEvent(
        DeserializationContext $context,
        object                 $object,
        ?string                $type = null,
    ): ObjectEvent {
        return new ObjectEvent($context, $object, $this->parseType($type ?? $object::class));
    }

    protected function getEventResult(Event $event): mixed
    {
        /** @var TypeArray $type */
        $type     = $event->getType();
        $metadata = $event->getContext()->getMetadataFactory()->getMetadataForClass($type['name']);
        if (!$metadata instanceof ClassMetadata) {
            throw new \RuntimeException('Cannot get valid metadata for class: ' . $type['name']);
        }

        $visitor = $event->getVisitor();
        if ($visitor instanceof SerializationVisitorInterface && $event instanceof ObjectEvent) {
            return $visitor->endVisitingObject($metadata, $event->getObject(), $type);
        }

        if ($visitor instanceof DeserializationVisitorInterface) {
            return $visitor->endVisitingObject(
                $metadata,
                $event instanceof ObjectEvent ? $event->getObject() : [],
                $type
            );
        }

        return null;
    }
}
