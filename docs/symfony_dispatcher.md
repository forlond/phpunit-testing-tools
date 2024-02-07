# Symfony/EventDispatcher

## Integration

- Use the `TestEventDispatcher` to perform expectations to any `EventDispatcherInterface` instance.

## TestEventDispatcher

Use the `expect` method to start an expectation definition.

```php
public function expect(Constraint|string $event): self
```

It is possible to continue defining the expectation with the following methods.

```php
public function name(Constraint|string $name): self
```

Assert the event name.

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
