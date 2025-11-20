# Psr/Log

## Integration

Use the `TestLogger` for any service that requires a `LoggerInterface` instance.

```php
class MyClass implements LoggerAwareInterface
{
    use LoggerAwareTrait;
}

$logger  = new TestLogger();
$service = new MyClass();
$service->setLogger($logger);
```

The `TestLogger` collects all log messages when using the `LoggerInterface` methods.

## TestLogger

```php
public function expect(string $level, Constraint|\Stringable|string $message, Constraint|array|null $context): self
```

Use the method `expect` to assert that a log message will be collected. The `expect` order invocation is relevant,
but it can be disabled by using the method `disableStrictSequence`.

---

```php
public function assert(): void
```

Finally, when all the assertions are in place, call the `assert` method.

In case the number of assertions do not match the number of collected logs, then the test will fail.
This is the default behaviour, but it can be disabled by using the `disableStrictSize` method.

> [!NOTE]
> For the non-strict sequence mode when a log message matches an assertion, then that assertion is not considered
> again for the remaining log messages.

> [!NOTE]
> When a log message is not found for an assertion, then the test fails.

### Examples

Example: strict sequence and strict size, one log and one assertion at same position.

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);

$logger
    ->expect('info', 'message', ['foobar' => true])
    ->assert()
;
```

Example: strict sequence and non-strict size, two logs and one assertion at the same position.

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);
$logger->info('other');

$logger
    ->disableStrictSize()
    ->expect('info', 'message', ['foobar' => true])
    ->assert()
;
```

Example: non-strict sequence and strict size, two logs and two assertions at any position.

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);
$logger->info('other');

$logger
    ->disableStrictSequence()
    ->expect('info', 'other', null)
    ->expect('info', 'message', ['foobar' => true])
    ->assert()
;
```

Example: non-strict sequence and non-strict size, two logs and one assertions at any position.

```php
$logger = new TestLogger();
$logger->info('other');
$logger->info('message', ['foobar' => true]);

$logger
    ->disableStrictSequence()
    ->disableStrictSize()
    ->expect('info', 'message', ['foobar' => true])
    ->assert()
;
```

Example: The test will fail because the assertion cannot be found in the collected logs.

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);

$logger
    ->expect('warning', 'other', null)
    ->assert()
;
```

Example: The test will fail because the same assertion was added more than once.

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);

$logger
    ->expect('info', 'message', ['foobar' => true])
    ->expect('info', 'message', ['foobar' => true])
    ->assert()
;
```

### Message Constraints

The `message` assertion can be either a `string` or a PHPUnit `Constraint`.

Example: Assert that the message is identical.

```php
$logger = new TestLogger();
$logger->info('There was a problem the day: 2023-10-10');

$logger
    ->expect('info', 'There was a problem the day: 2023-10-10', null)
    ->assert()
;
```

Example: Assert that the message start with a substring

```php
$logger = new TestLogger();
$logger->info('There was a problem the day: 2023-10-10');

$logger
    ->expect('info', new StringStartsWith('There was a problem the day: '), null)
    ->assert()
;
```

### Context Constraints

The `context` assertion can be either an `array` or a PHPUnit `Constraint`. To disable the `context` assertion, just
use a `null` value.

When using an `array`, that `array` must be identical at all levels, otherwise the test will fail.

#### Examples

Example: Context is identical (test passes)

```php
$logger = new TestLogger();
$logger->info('message', ['one' => 1, 'two' => 2]);

$logger
    ->expect('info', 'message', ['one' => 1, 'two' => 2])
    ->assert()
;
```

Example: Context is partially the same (test fails)

```php
$logger = new TestLogger();
$logger->info('message', ['one' => 1, 'two' => 2]);

$logger
    ->expect('info', 'message', ['one' => 1])
    ->assert()
;
```

Using an `array` may be not very flexible when you only want to check part of the context or when the values are not
easy to assert. In this case, it is recommended to use PHPUnit constraints.

This repository provides `ArrayContains`, a versatile constraint suitable for several use cases.

This constraint allows to declare an array shape. When the `strict` mode is `true`, then the array shape must match all
the keys from the original array. The `strict` mode can be disabled to assert partial associative arrays.

Example: Context keys matches (constraint version, test passes)

```php
$logger = new TestLogger();
$logger->info('message', ['one' => 1, 'two' => 2]);

$logger
    ->expect(
        'info',
        'message',
        new ArrayContains(['one' => 1, 'two' => 2])
    )
    ->assert()
;
```

Example: Context keys matches partially (test passes)

```php
$logger = new TestLogger();
$logger->info('message', ['one' => 1, 'two' => 2]);

$logger
    ->expect(
        'info',
        'message',
        new ArrayContains(['one' => 1], false)
    )
    ->assert()
;
```

The `ArrayContains` allows to assert the array values with any PHPUnit constraint.

Example: Value is added internally, so cannot use the same instance.

```php
function addLog(LoggerInterface $logger) {
    $logger->info('message', ['one' => new \stdClass()]);
}

$logger = new TestLogger();
addLog($logger);

$logger
    ->expect(
        'info',
        'message',
        new ArrayContains(['one' => self::isInstanceOf(\stdClass::class)])
    )
    ->assert()
;
```

The `ArrayContains` can be used at any array level with the same previous features.

Example: Nested array, first level strict mode, second level non-strict mode.

```php
$logger = new TestLogger();
$logger->info('message', ['one' => ['es' => 'uno', 'pt' => 'um']]);

$logger
    ->expect(
        'info',
        'message',
        new ArrayContains([
            'one' => new ArrayContains(['es' => 'uno'], false),
        ])
    )
    ->assert()
;
```

## Reset

Use the `reset` method to restore the logger.

```php
public function reset(): void
```
