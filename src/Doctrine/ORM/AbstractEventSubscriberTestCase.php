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

    protected function createPostLoadEvent(TestEntityManager $manager, object $object): Event\PostLoadEventArgs
    {
        return new Event\PostLoadEventArgs($object, $manager);
    }

    protected function createLoadClassMetadataEvent(
        TestEntityManager $manager,
        ClassMetadata     $classMetadata,
    ): Event\LoadClassMetadataEventArgs {
        return new Event\LoadClassMetadataEventArgs($classMetadata, $manager);
    }

    protected function createOnClassMetadataNotFoundEvent(
        TestEntityManager $manager,
        string            $className,
    ): Event\OnClassMetadataNotFoundEventArgs {
        return new Event\OnClassMetadataNotFoundEventArgs($className, $manager);
    }

    protected function createPrePersistEvent(TestEntityManager $manager, object $object): Event\PrePersistEventArgs
    {
        return new Event\PrePersistEventArgs($object, $manager);
    }

    protected function createPostPersistEvent(TestEntityManager $manager, object $object): Event\PostPersistEventArgs
    {
        return new Event\PostPersistEventArgs($object, $manager);
    }

    protected function createPreUpdateEvent(
        TestEntityManager $manager,
        object            $object,
        array             &$changeSet,
    ): Event\PreUpdateEventArgs {
        return new Event\PreUpdateEventArgs($object, $manager, $changeSet);
    }

    protected function createPostUpdateEvent(TestEntityManager $manager, object $object): Event\PostUpdateEventArgs
    {
        return new Event\PostUpdateEventArgs($object, $manager);
    }

    protected function createPreRemoveEvent(TestEntityManager $manager, object $object): Event\PreRemoveEventArgs
    {
        return new Event\PreRemoveEventArgs($object, $manager);
    }

    protected function createPostRemoveEvent(TestEntityManager $manager, object $object): Event\PostRemoveEventArgs
    {
        return new Event\PostRemoveEventArgs($object, $manager);
    }

    protected function createPreFlushEvent(TestEntityManager $manager): Event\PreFlushEventArgs
    {
        return new Event\PreFlushEventArgs($manager);
    }

    protected function createOnFlushEvent(TestEntityManager $manager): Event\OnFlushEventArgs
    {
        return new Event\OnFlushEventArgs($manager);
    }

    protected function createPostFlushEvent(TestEntityManager $manager): Event\PostFlushEventArgs
    {
        return new Event\PostFlushEventArgs($manager);
    }

    protected function createOnClearEvent(TestEntityManager $manager): Event\OnClearEventArgs
    {
        return new Event\OnClearEventArgs($manager);
    }
}
