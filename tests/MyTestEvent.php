<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Forlond\TestTools\Doctrine\ORM\AbstractEventSubscriberTestCase;
use Forlond\TestTools\Doctrine\ORM\TestConfiguration;
use Forlond\TestTools\Psr\Log\TestLogger;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class MyTestEvent extends AbstractEventSubscriberTestCase implements EventSubscriber
{
    public function testEM(): void
    {
        $logger = new TestLogger();
        $em     = $this->createEntityManager(
            function(TestConfiguration $configuration) use ($logger) {
                $configuration->platform = new PostgreSQLPlatform();
                $configuration->setMiddlewares([
                    new Middleware($logger),
                ]);
            }
        );

//        $object        = new MyObject();
        $em->getConnection()->setResults(['id_1' => 1, 'color_2' => 'red']);
        $object = $em->find(MyObject::class, 1);

        $object->color = 'green';

        $em->getUnitOfWork()->computeChangeSets();

        $event      = $this->createOnFlushEvent($em);
        $subscriber = $this->createSubscriber(null);
        $subscriber->onFlush($event);
    }

    public function getSubscribedEvents()
    {
    }

    public function onFlush(OnFlushEventArgs $event): void
    {
        $em  = $event->getObjectManager();
        $uow = $em->getUnitOfWork();
    }

    protected function createSubscriber(?callable $configure): MyTestEvent
    {
        return $this;
    }
}
