<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Fixtures\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnClearEventArgs;
use Doctrine\ORM\Events;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
class DoctrineEventSubscriber implements EventSubscriber
{
    public ?OnClearEventArgs $event = null;

    public function getSubscribedEvents(): array
    {
        return [
            Events::onClear,
        ];
    }

    public function onClear(OnClearEventArgs $event): void
    {
        $this->event = $event;
    }
}
