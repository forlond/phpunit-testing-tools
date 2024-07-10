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

In case the number of assertions do not match the number of sent messages, then the test will fail.
This is the default behaviour, but it can be disabled by using the `disableStrictSize` method.

> [!NOTE]
> For the non-strict sequence mode when a sent message matches a constraint, then that expectation is not considered
> again for the remaining sent messages.

> [!NOTE]
> When a sent message is not found for an assertion, then the test fails.

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
> More than constraint can be used to assert different message properties.
> Use `LogicalAnd` or `LogicalOr` PHPUnit constraints to combine several constraints.

Example: Assert a message is sent.

```php
$test = new TestMailer();

$test
    ->expect('Hello!')
    ->assert()
;
```

Example: Assert a `Message` is sent, and the message contains some body **AND** some header.

```php
$test = new TestMailer();

$test
    ->expect(LogicalAnd::fromConstraints(
        new MessageBodyContains('Hello!'),
        new EmailHeaderSame('my-header', 'value')
    ))
    ->assert()
;
```

Example: Assert a `Message` is sent, and the message contains some body **OR** some header.

```php
$test = new TestMailer();

$test
    ->expect(LogicalOr::fromConstraints(
        new MessageBodyContains('Hello!'),
        new EmailHeaderSame('my-header', 'value')
    ))
    ->assert()
;
```

Example: Assert a `Message` is sent with an `Envelope` with a sender and recipient

```php
$test = new TestMailer();

$test
    ->expect('Hello!')
    ->envelope(LogicalAnd::fromConstraints(
        new EnvelopeSenderSame('sender@example.com'),
        new EmailHasRecipient('foobar@example.com'),
        new EmailRecipientCount(1)
    ))
    ->assert()
;
```
