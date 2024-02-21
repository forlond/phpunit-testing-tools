# Symfony/EventDispatcher

## Integration

- Use the `TestEventDispatcher` to perform expectations to any `EventDispatcherInterface` instance.

## TestEventDispatcher

```php
public function expect(Constraint|string $event): self
```

Use the `expect` method to start an expectation definition.

The `expect` order invocation is relevant, but it can be disabled by using the method `disableStrictSequence`.

Also, it is possible to continue defining the expectation with the following methods.

---

```php
public function name(Constraint|string $name): self
```

Assert the event name.

---

```php
public function assert(): void
```

Finally, when all the expectations are in place, call the `assert` method.

In case the number of expectations do not match the number of collected logs, then the entire expectation will fail.
This is the default behaviour, but it can be disabled by using the `disableStrictSize` method.

> [!NOTE]
> For the non-strict sequence mode when an event matches a constraint, then that expectation is not considered again
> for the remaining events.

> [!NOTE]
> When a event is not found for an expectation, then the test fails.


Example: Assert an event class is dispatched.

```php
$test = new TestEventDispatcher();

$test
    ->expect(MyEvent::class)
    ->assert()
;
```

Example: Assert an event class is dispatched for a specific event name.

```php
$test = new TestEventDispatcher();

$test
    ->expect(MyEvent::class)
    ->name('app.event_name')
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
        })
    )
    ->assert()
;
```
