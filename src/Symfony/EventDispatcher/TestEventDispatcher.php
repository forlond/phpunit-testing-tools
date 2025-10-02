<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\EventDispatcher;

use Forlond\TestTools\AbstractTestGroup;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestEventDispatcher extends AbstractTestGroup implements EventDispatcherInterface
{
    protected const GROUP_NAME = 'event dispatcher';

    private array $events = [];

    public function expect(Constraint|string $event, Constraint|string|null $name): self
    {
        $this->next();

        if (is_string($event)) {
            $event = new IsInstanceOf($event);
        }

        $this->set('event', $event, static fn(array $expect) => $expect['event']);
        if (null !== $name) {
            $this->set('name', $name, static fn(array $expect) => $expect['name']);
        }

        return $this;
    }

    public function dispatch(object $event, ?string $eventName = null): object
    {
        $this->events[] = ['event' => $event, 'name' => $eventName ?? $event::class];

        return $event;
    }

    public function addListener(string $eventName, callable $listener, int $priority = 0): void
    {
        throw new \BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    public function addSubscriber(EventSubscriberInterface $subscriber): void
    {
        throw new \BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    public function removeListener(string $eventName, callable $listener): void
    {
        throw new \BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber): void
    {
        throw new \BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    public function getListeners(?string $eventName = null): array
    {
        return [];
    }

    public function getListenerPriority(string $eventName, callable $listener): ?int
    {
        return null;
    }

    public function hasListeners(?string $eventName = null): bool
    {
        return false;
    }

    protected function getValue(): array
    {
        return $this->events;
    }
}
