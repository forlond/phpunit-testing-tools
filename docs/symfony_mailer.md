# Symfony/Mailer

## Integration

- Use the `TestMailer` to perform assertions to any `MailerInterface` instance.

## TestMailer

```php
public function expect(Constraint|string $message): self
```

Use the method `expect` to assert that a message will be sent. The `expect` order invocation is relevant,
but it can be disabled by using the method `disableStrictSequence`.

---

```php
public function envelope(Constraint $envelope): self
```

Use the method `envelope` to assert that an envelope will be sent with the main message.

---

```php
public function assert(): void
```

Finally, when all the expectations are in place, call the `assert` method.

In case the number of assertions do not match the number of messages, then the test will fail.
This is the default behaviour, but it can be disabled by using the `disableStrictSize` method.

> [!NOTE]
> For the non-strict sequence mode when a message matches a constraint, then that expectation is not considered
> again for the remaining messages.

> [!NOTE]
> When a message is not found for an assertion, then the test fails.

### Constraints

Symfony provides `Email` PHPUnit constraints.

- `EmailAddressContains`
- `EmailAttachmentCount`
- `EmailHasHeader`
- `EmailHeaderSame`
- `EmailHtmlBodyContains`
- `EmailSubjectContains`
- `EmailTextBodyContains`

> [!NOTE]
> Only `EmailAddressContains`, `EmailHasHeader` and `EmailHeaderSame` are compatible with `Message` instances.
> Check Symfony docs for more information.

This integration also provides the following PHPUnit constraints:

- `MessageBodyContains`, this constraint can be used for `RawMessage`, `Message` and `Email` instances.
- `EnvelopeHasRecipient`, this constraint can be used for `Envelope` instances.
- `EnvelopeRecipientCount`, this constraint can be used for `Envelope` instances.
- `EnvelopeSenderSame`, this constraint can be used for `Envelope` instances.

> [!NOTE]
> The `EmailTextBodyContains` is used internally in `MessageBodyContains` when the message instance is `Email`.

> [!NOTE]
> More than one constraint can be used to assert different message properties.
> Use `LogicalAnd` or `LogicalOr` PHPUnit constraints to combine several constraints.

Example: Assert a message is sent.

```php
$test = new TestMailer();
$test->send(new RawMessage('Hello!'));

$test
    ->expect('Hello!')
    ->assert()
;
```

Example: Assert a `Message` contains some body **AND** some header.

```php
$test = new TestMailer();
$test->send(new Message(new Headers(new UnstructuredHeader('my-header', 'value')), new TextPart('Hello!')));

$test
    ->expect(self::logicalAnd(
        new MessageBodyContains('Hello!'),
        new EmailHeaderSame('my-header', 'value')
    ))
    ->assert()
;
```

Example: Assert a `Message` contains body **OR** some header.

```php
$test = new TestMailer();
$test->send(new Message(new Headers(new UnstructuredHeader('my-header', 'value')), new TextPart('Hello!')));

$test
    ->expect(self::logicalOr(
        new MessageBodyContains('Hel'),
        new EmailHeaderSame('my-header', 'value')
    ))
    ->assert()
;
```

Example: Assert a `Message` is sent with an `Envelope` with a sender and recipient

```php
$envelope = new Envelope(new Address('sender@example.com'), [new Address('foobar@example.com')]);

$test = new TestMailer();
$test->send(new RawMessage('Hello!'), $envelope);

$test
    ->expect('Hello!')
    ->envelope(self::logicalAnd(
        new EnvelopeSenderSame('sender@example.com'),
        new EmailHasRecipient('foobar@example.com'),
        new EmailRecipientCount(1)
    ))
    ->assert()
;
```
