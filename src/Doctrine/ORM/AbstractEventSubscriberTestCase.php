<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\ORM;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractEventSubscriberTestCase extends AbstractEntityManagerTestCase
{
    abstract protected function createSubscriber(?callable $configure): EventSubscriber;

    final protected function createPostLoadEvent(TestEntityManager $manager, object $object): Event\PostLoadEventArgs
    {
        return new Event\PostLoadEventArgs($object, $manager);
    }

    final protected function createLoadClassMetadataEvent(
        TestEntityManager $manager,
        ClassMetadata     $classMetadata,
    ): Event\LoadClassMetadataEventArgs {
        return new Event\LoadClassMetadataEventArgs($classMetadata, $manager);
    }

    final protected function createOnClassMetadataNotFoundEvent(
        TestEntityManager $manager,
        string            $className,
    ): Event\OnClassMetadataNotFoundEventArgs {
        return new Event\OnClassMetadataNotFoundEventArgs($className, $manager);
    }

    final protected function createPrePersistEvent(
        TestEntityManager $manager,
        object            $object,
    ): Event\PrePersistEventArgs {
        return new Event\PrePersistEventArgs($object, $manager);
    }

    final protected function createPostPersistEvent(
        TestEntityManager $manager,
        object            $object,
    ): Event\PostPersistEventArgs {
        return new Event\PostPersistEventArgs($object, $manager);
    }

    final protected function createPreUpdateEvent(
        TestEntityManager $manager,
        object            $object,
        array             &$changeSet,
    ): Event\PreUpdateEventArgs {
        return new Event\PreUpdateEventArgs($object, $manager, $changeSet);
    }

    final protected function createPostUpdateEvent(
        TestEntityManager $manager,
        object            $object,
    ): Event\PostUpdateEventArgs {
        return new Event\PostUpdateEventArgs($object, $manager);
    }

    final protected function createPreRemoveEvent(TestEntityManager $manager, object $object): Event\PreRemoveEventArgs
    {
        return new Event\PreRemoveEventArgs($object, $manager);
    }

    final protected function createPostRemoveEvent(
        TestEntityManager $manager,
        object            $object,
    ): Event\PostRemoveEventArgs {
        return new Event\PostRemoveEventArgs($object, $manager);
    }

    final protected function createPreFlushEvent(TestEntityManager $manager): Event\PreFlushEventArgs
    {
        return new Event\PreFlushEventArgs($manager);
    }

    final protected function createOnFlushEvent(TestEntityManager $manager): Event\OnFlushEventArgs
    {
        return new Event\OnFlushEventArgs($manager);
    }

    final protected function createPostFlushEvent(TestEntityManager $manager): Event\PostFlushEventArgs
    {
        return new Event\PostFlushEventArgs($manager);
    }

    final protected function createOnClearEvent(TestEntityManager $manager): Event\OnClearEventArgs
    {
        return new Event\OnClearEventArgs($manager);
    }
}
