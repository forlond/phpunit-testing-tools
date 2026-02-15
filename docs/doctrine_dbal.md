# Doctrine/DBAL

## Integration

Use one of the following abstract test cases:

- `AbstractDBALTestCase` for general DBAL purposes.

## AbstractDBALTestCase

Provides a base for any test that uses a DBAL connection.

```php
final protected function createConnection(
    ?Configuration $configuration = null,
    ?AbstractPlatform $platform = null,
): TestDBALConnection;
```

Creates a new `TestDBALConnection` instance which extends from `Doctrine\DBAL\Connection`.

It is possible to pass a custom `Doctrine\DBAL\Configuration`, otherwise the `createConfiguration` method will be used.

It is possible to pass a custom `Doctrine\DBAL\Platforms\AbstractPlatform`, otherwise the `createPlatform` method will
be used.

---

```php
protected function createConfiguration(): Configuration
```

Override this method if the class test needs the same configuration for all the test cases.

---

```php
protected function createPlatform(): AbstractPlatform
```

Override this method if the class test needs the same platform for all the test cases.

> [!IMPORTANT]
> The `TestDBALConnection` has limited functionalities, but it is possible to configure the result of any statement.
> Use `TestDBALConnection::setResult` before using any other method that returns results.

Example:

```php
final class MyClassTest extends AbstractDBALTestCase
{
    public function testStatement(): void
    {
        $connection = $this->createConnection();

        $connection->setResult(['first', 'second'], ['other_first', 'other_second']);
        $value = $connection->fetchFirstColumn('SELECT * FROM foobar');

        self::assertSame(['first', 'other_first'], $value);
    }
}
```
