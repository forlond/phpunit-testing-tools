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

## Assertions

Use the method `expect` to indicate that a log message is expected to be collected. The `expect` order invocation is not
relevant, so it is not necessary to have the same order in which the log messages are collected.

```php
public function expect(string $level, Constraint|\Stringable|string $message, Constraint|array|null $context): self
```

Finally, when all the expectations are in place, the `assert` method needs be used. There are two modalities:

```php
public function assert(bool $strict = true): void
```

- When the `strict` mode is `true`, there must be an expectation for all the log messages, otherwise the test will fail.
- When the `strict` mode is `false`, only the expected logs will be checked against the collected log messages,
  regardless of any unchecked log messages.

The default behaviour for the `strict` mode is `true`.

> [!NOTE]
> When a log message matches something expected, that log message is not considered again for the remaining
> expectations. The test fails if the same expectation is added more than once.

> [!NOTE]
> When a log message is not found for an expectation, then the test fails.

### Examples

Example: The test will pass (strict mode)

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);

$logger
    ->expect('info', 'message', ['foobar' => true])
    ->assert()
;
```

Example: The test will pass because the expectation is found in the collected logs (non-strict mode)

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);
$logger->info('other');

$logger
    ->expect('info', 'message', ['foobar' => true])
    ->assert(false)
;
```

Example: The test will fail because two logs were collected, but there was only one expectation (strict mode)

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);
$logger->info('other');

$logger
    ->expect('info', 'message', ['foobar' => true])
    ->assert()
;
```

Example: The test will fail because the expectation cannot be found in the collected logs (strict mode)

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);

$logger
    ->expect('warning', 'other')
    ->assert()
;
```

Example: The test will fail because the expectation cannot be found in the collected logs (non-strict mode)

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);

$logger
    ->expect('warning', 'other')
    ->assert(false)
;
```

Example: The test will fail because there is the same expectation more than once (non-strict mode)

```php
$logger = new TestLogger();
$logger->info('message', ['foobar' => true]);

$logger
    ->expect('info', 'message', ['foobar' => true])
    ->expect('info', 'message', ['foobar' => true])
    ->assert(false)
;
```

### Message Constraints

The `message` expectation can be either a `string` or a PHPUnit `Constraint`.

Example: Assert that the message is identical.

```php
$logger = new TestLogger();
$logger->info('There was a problem the day: 2023-10-10');

$logger
    ->expect('info', 'There was a problem the day: 2023-10-10'), null)
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

The `context` expectation can be either an `array` or a PHPUnit `Constraint`. To disable the `context` assertion, just
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

This repository provides `AssociativeArrayContains`, a versatile constraint suitable for several use cases.

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
        new AssociativeArrayContains(['one' => 1, 'two' => 2])
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
        new AssociativeArrayContains(['one' => 1], false)
    )
    ->assert()
;
```

The `AssociativeArrayContains` allows to assert the array values with any PHPUnit constraint.

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
        new AssociativeArrayContains(['one' => self::assertInstanceOf(\stdClass::class)])
    )
    ->assert()
;
```

The `AssociativeArrayContains` can be used at any array level with the same previous features.

Example: Nested array, first level strict mode, second level non-strict mode.

```php
$logger = new TestLogger();
$logger->info('message', ['one' => ['es' => 'uno', 'pt' => 'um']]);

$logger
    ->expect(
        'info',
        'message',
        new AssociativeArrayContains([
            'one' => new AssociativeArrayContains(['es' => 'uno'], false),
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
