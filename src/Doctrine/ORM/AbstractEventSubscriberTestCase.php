<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\ORM;

use Doctrine\Common\EventSubscriber;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractEventSubscriberTestCase extends AbstractEntityManagerTestCase
{
    use TestORMEventsTrait;

    abstract protected function createSubscriber(?callable $configure): EventSubscriber;
}
