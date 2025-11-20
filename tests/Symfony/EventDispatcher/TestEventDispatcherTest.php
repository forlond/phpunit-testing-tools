<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Symfony\EventDispatcher;

use Forlond\TestTools\Symfony\EventDispatcher\TestEventDispatcher;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class TestEventDispatcherTest extends TestCase
{
    public function testExpectEvent(): void
    {
        $test = new TestEventDispatcher();
        $test->dispatch(new MyEvent());

        $test
            ->expect(MyEvent::class, null)
            ->assert()
        ;
    }

    public function testExpectEventFail(): void
    {
        $this->expectException(AssertionFailedError::class);

        $test = new TestEventDispatcher();
        $test->dispatch(new MyEvent());

        $test
            ->expect(self::class, null)
            ->assert()
        ;
    }

    public function testExpectEventConstraint(): void
    {
        $test = new TestEventDispatcher();
        $test->dispatch($event = new MyEvent());

        $test
            ->expect($this->identicalTo($event), null)
            ->assert()
        ;
    }

    public function testExpectEventConstraintFail(): void
    {
        $this->expectException(AssertionFailedError::class);

        $test = new TestEventDispatcher();
        $test->dispatch($event = new MyEvent());

        $test
            ->expect($this->logicalNot($this->identicalTo($event)), null)
            ->assert()
        ;
    }

    public function testExpectEventName(): void
    {
        $test = new TestEventDispatcher();
        $test->dispatch(new MyEvent(), 'app.event_name');

        $test
            ->expect(MyEvent::class, 'app.event_name')
            ->assert()
        ;
    }

    public function testExpectEventNameFail(): void
    {
        $this->expectException(AssertionFailedError::class);

        $test = new TestEventDispatcher();
        $test->dispatch(new MyEvent(), 'app.event_name');

        $test
            ->expect(MyEvent::class, 'app.foo_bar')
            ->assert()
        ;
    }

    public function testExpectEventNameConstraint(): void
    {
        $test = new TestEventDispatcher();
        $test->dispatch($event = new MyEvent(), 'app.event_name');

        $test
            ->expect($this->identicalTo($event), $this->stringStartsWith('app.'))
            ->assert()
        ;
    }

    public function testExpectEventNameConstraintFail(): void
    {
        $this->expectException(AssertionFailedError::class);

        $test = new TestEventDispatcher();
        $test->dispatch(new MyEvent(), 'app.event_name');

        $test
            ->expect(MyEvent::class, $this->stringStartsWith('foo.'))
            ->assert()
        ;
    }

    public function testAddListener(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $test = new TestEventDispatcher();
        $test->addListener('test', static fn() => true);
    }

    public function testAddSubscriber(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $test       = new TestEventDispatcher();
        $subscriber = new class() implements EventSubscriberInterface {
            public static function getSubscribedEvents(): array
            {
                return [];
            }
        };
        $test->addSubscriber($subscriber);
    }

    public function testRemoveListener(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $test = new TestEventDispatcher();
        $test->removeListener('test', static fn() => true);
    }

    public function testRemoveSubscriber(): void
    {
        $this->expectException(\BadMethodCallException::class);

        $test       = new TestEventDispatcher();
        $subscriber = new class() implements EventSubscriberInterface {
            public static function getSubscribedEvents(): array
            {
                return [];
            }
        };
        $test->removeSubscriber($subscriber);
    }

    public function testListeners(): void
    {
        $test = new TestEventDispatcher();

        self::assertEmpty($test->getListeners());
    }

    public function testListenerPriority(): void
    {
        $test = new TestEventDispatcher();

        self::assertNull($test->getListenerPriority('test', static fn() => true));
    }

    public function testHasListeners(): void
    {
        $test = new TestEventDispatcher();

        self::assertFalse($test->hasListeners('test'));
    }
}

/** @internal */
class MyEvent extends Event
{
}
