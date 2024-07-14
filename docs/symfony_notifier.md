# Symfony/Notifier

## Integration

- Use the `TestNotifier` to perform assertions to any `NotifierInterface` instance.

## TestNotifier

```php
public function expect(Constraint|string $subject): self
```

Use the method `expect` to assert that a notification will be sent with some subject. The `expect` order invocation is
relevant, but it can be disabled by using the method `disableStrictSequence`.

Example: Assert the notification subject is identical.

```php
$test = new TestNotifier();
$test->send(new Notification('My Subject'));

$test
    ->expect('My Subject')
    ->assert()
;
```

Example: Assert the notification subject contains a string.

```php
$test = new TestNotifier();
$test->send(new Notification('My Subject'));

$test
    ->expect(self::stringContains('My'))
    ->assert()
;
```

---

```php
public function content(Constraint|string $content): self
```

Use the method `content` to assert the notification content.

Example: Assert the notification content is identical.

```php
$notification = new Notification('My Subject');
$notification->content('Notification content');

$test = new TestNotifier();
$test->send($notification);

$test
    ->expect('My Subject')
    ->content('Notification content')
    ->assert()
;
```

Example: Assert the notification content contains a string.

```php
$notification = new Notification('My Subject');
$notification->content('Notification content');

$test = new TestNotifier();
$test->send($notification);

$test
    ->expect('My Subject')
    ->content(self::stringContains('content'))
    ->assert()
;
```

---

```php
public function importance(Constraint|string $importance): self
```

Use the method `importance` to assert the notification importance.

Example: Assert the notification importance is identical.

```php
$notification = new Notification('My Subject');
$notification->importance(Notification::IMPORTANCE_HIGH);

$test = new TestNotifier();
$test->send($notification);

$test
    ->expect('My Subject')
    ->importance('high')
    ->assert()
;
```

Example: Assert the notification importance is one of two possible values.

```php
$notification = new Notification('My Subject');
$notification->importance(Notification::IMPORTANCE_HIGH);

$test = new TestNotifier();
$test->send($notification);

$test
    ->expect('My Subject')
    ->importance(self::logicalOr(self::identicalTo('high'), self::identicalTo('low')))
    ->assert()
;
```

---

```php
public function emoji(Constraint|string $emoji): self
```

Use the method `emoji` to assert the notification emoji.

Example: Assert the notification emoji is identical.

```php
$notification = new Notification('My Subject');
$notification->emoji(🐶);

$test = new TestNotifier();
$test->send($notification);

$test
    ->expect('My Subject')
    ->emoji(🐶)
    ->assert()
;
```

Example: Assert the notification emoji is one of two possible values.

```php
$notification = new Notification('My Subject');
$notification->emoji(🐶);

$test = new TestNotifier();
$test->send($notification);

$test
    ->expect('My Subject')
    ->emoji(self::logicalOr(self::identicalTo(🐶), self::identicalTo(🐱)))
    ->assert()
;
```

---

```php
public function exception(
    string                 $class,
    Constraint|string|null $mesage = null,
    Constraint|int|null    $code = null,
): self
```

Use the method `exception` to assert the notification exception.

> [!NOTE]
> The exception is a FlattenException.

Example: Assert the notification exception is equal or contains a message.

```php
$notification = new Notification();
$notification->exception(new \LogicException('Error', 100))

$test = new TestNotifier();
$test->send($notification);

$test
    ->expect('LogicException: Error')
    ->exception(LogicException::class, 'Error', 100)
    ->assert()
;
```

---

```php
public function channels(Constraint|array $channels): self
```

Use the method `channels` to assert the notification channels.

Example: Assert the notification channels is identical

```php
$test = new TestNotifier();
$test->send(new Notification('My Subject', ['channel1', 'channel2']));

$test
    ->expect('My Subject')
    ->channels(['channel1', 'channel2'])
    ->assert()
;
```

Example: Assert the notification channels has a number of channels.

```php
$test = new TestNotifier();
$test->send(new Notification('My Subject', ['channel1', 'channel2']));

$test
    ->expect('My Subject')
    ->channels(self::countOf(2))
    ->assert()
;
```

---

```php
public function custom(callable $callback): self
```

Use the method `custom` to assert custom constraints for the notification instance.

> [!NOTE]
> Use this method if you have custom Notification instances.

Example: Assert the notification instance.

```php
$test = new TestNotifier();
$test->send(new MyNotification('My Subject'));

$test
    ->expect('My Subject')
    ->custom(static function(Notification $notification) {
        self::assertInstanceOf(MyNotification::class, $notification);

        return true;
    })
    ->assert()
;
```

---

```php
public function recipients(Constraint ...$recipients): self
```

Use the method `recipients` to assert each notification recipient.

> [!NOTE]
> Not passing any recipient asserts the number of recipients is zero.

This integration also provides the following PHPUnit constraints for the `recipients` method.

- `NotificationNoRecipient`, asserts the recipient is a `NoRecipient` instance.
- `NotificationEmailRecipient`, asserts the recipient is an `EmailRecipientInterface` instance and the email asserts
  the configured value/constraint.
- `NotificationSmsRecipient`, asserts the recipient is an `SmsRecipientInterface` instance and the phone asserts
  the configured value/constraint.

> [!NOTE]
> More than one constraint can be used to assert different recipient properties.
> Use `LogicalAnd` or `LogicalOr` PHPUnit constraints to combine several constraints.

Example: Assert recipients

```php
$test = new TestNotifier();
$test->send(new Notification(), new NoRecipient(), new Recipient('email@test.dev', '2345678901'));

$test
    ->expect('')
    ->recipients(
        new NotificationNoRecipient(),
        self::logicalAnd(
            new NotificationEmailRecipient('email@test.dev'),
            new NotificationSmsRecipient('2345678901')
        )
    )
    ->assert()
;
```

---

```php
public function assert(): void
```

Finally, when all the expectations are in place, call the `assert` method.

In case the number of assertions do not match the number of notifications, then the test will fail.
This is the default behaviour, but it can be disabled by using the `disableStrictSize` method.

> [!NOTE]
> For the non-strict sequence mode when a notification matches a constraint, then that expectation is not considered
> again for the remaining notifications.

> [!NOTE]
> When a notification is not found for an assertion, then the test fails.
