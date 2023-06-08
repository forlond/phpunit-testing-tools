<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\EventDispatcher\Event;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractEventSubscriberTestCase extends AbstractSerializerTestCase
{
    abstract protected function createSubscriber(?callable $configure): EventSubscriberInterface;

    final protected function createPreSerializeEvent(
        TestSerializationContext $context,
        object                   $object,
    ): PreSerializeEvent {
        $context->startVisiting($object);

        return new PreSerializeEvent($context, $object, ['name' => get_class($object)]);
    }

    final protected function createPreDeserializeEvent(
        TestDeserializationContext $context,
        mixed                      $data,
        string                     $type,
    ): PreDeserializeEvent {
        $context->increaseDepth();

        return new PreDeserializeEvent($context, $data, ['name' => $type]);
    }

    final protected function createPostSerializeEvent(
        TestSerializationContext $context,
        object                   $object,
    ): ObjectEvent {
        return new ObjectEvent($context, $object, ['name' => get_class($object)]);
    }

    final protected function createPostDeserializeEvent(
        TestDeserializationContext $context,
        object                     $object,
    ): ObjectEvent {
        return new ObjectEvent($context, $object, ['name' => get_class($object)]);
    }

    final protected function getResult(Event $event): mixed
    {
        $metadata = $event->getContext()->getMetadataFactory()->getMetadataForClass($event->getType()['name']);
        $visitor  = $event->getVisitor();

        if ($visitor instanceof SerializationVisitorInterface && $event instanceof ObjectEvent) {
            return $visitor->endVisitingObject($metadata, $event->getObject(), $event->getType());
        }

        if ($visitor instanceof DeserializationVisitorInterface) {
            return $visitor->endVisitingObject(
                $metadata,
                $event instanceof ObjectEvent ? $event->getObject() : [],
                $event->getType()
            );
        }

        return null;
    }
}
