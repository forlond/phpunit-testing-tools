# Symfony/EventDispatcher

## Integration

- Use the `TestEventDispatcher` to perform assertions to any `EventDispatcherInterface` instance.

## TestEventDispatcher

> [!IMPORTANT]
> The `TestEventDispatcher` does not dispatch any listener/subscriber.
> It is not possible to register listeners/subscribers to this instance.

> [!NOTE]
> The `TestEventDispatcher` can be used as the third argument for any listener/subscriber methods.

```php
public function expect(Constraint|string $event, Constraint|array|null $name): self
```

Use the method `expect` to assert that a event will be dispatched. The `expect` order invocation is relevant,
but it can be disabled by using the method `disableStrictSequence`.

The `name` argument is optional, use a `null` value to disable name assertions.

---

```php
public function assert(): void
```

Finally, when all the expectations are in place, call the `assert` method.

In case the number of assertions do not match the number of dispatched events, then the test will fail.
This is the default behaviour, but it can be disabled by using the `disableStrictSize` method.

> [!NOTE]
> For the non-strict sequence mode when a dispatched event matches a constraint, then that expectation is not considered
> again for the remaining dispatched events.

> [!NOTE]
> When a dispatched event is not found for an assertion, then the test fails.


Example: Assert an event class is dispatched.

```php
$test = new TestEventDispatcher();

$test
    ->expect(MyEvent::class, null)
    ->assert()
;
```

Example: Assert an event class is dispatched for a specific event name.

```php
$test = new TestEventDispatcher();

$test
    ->expect(MyEvent::class, 'app.event_name')
    ->assert()
;
```

Example: Assert an event is dispatched but using a custom constraint.

```php
$test = new TestEventDispatcher();

$test
    ->expect(
        new Callback(static function(MyEvent $event) {
            self::assertSame('foobar', $event->getValue())
        }),
        null
    )
    ->assert()
;
```
