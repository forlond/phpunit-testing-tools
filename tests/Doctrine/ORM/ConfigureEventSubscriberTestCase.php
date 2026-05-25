<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\ORM;

use Forlond\TestTools\Doctrine\ORM\AbstractEventSubscriberTestCase;
use Forlond\TestTools\Tests\Fixtures\Doctrine\DoctrineEventSubscriber;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class ConfigureEventSubscriberTestCase extends AbstractEventSubscriberTestCase
{
    public function testPrePersist(): void
    {
        $em    = $this->createEntityManager();
        $event = $this->createOnClearEvent($em);

        $subscriber = $this->createSubscriber(null);
        $subscriber->onClear($event);

        self::assertSame($event, $subscriber->event);
    }

    protected function createSubscriber(?callable $configure): DoctrineEventSubscriber
    {
        return new DoctrineEventSubscriber();
    }
}
