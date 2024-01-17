# Doctrine/ORM

## Integration

Use one of the following abstract test cases:

- `AbstractDBALTestCase` for general DBAL purposes.
- `AbstractEntityManagerTestCase` for general ORM purposes.
- `AbstractEventSubscriberTestCase` for `EventSubscriber` implementations.

## AbstractDBALTestCase

Provides a base for any test that uses a DBAL connection.

```php
final protected function createConnection(
    ?Configuration $configuration = null,
    ?AbstractPlatform $platform = null,
): TestDBALConnection;
```

Creates a new `TestDBALConnection` instance which extends from `Doctrine\DBAL\Connection`.

It is possible to pass a custom `Doctrine\DBAL\Configuration`, otherwise the `createConnection` method will be used.

It is possible to pass a custom `Doctrine\DBAL\Platforms\AbstractPlatform`, otherwise the `createPlatform` method will
be used.

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

        $connection->setResults(['first', 'second'], ['other_first', 'other_second']);
        $value = $connection->fetchFirstColumn('SELECT * FROM foobar');

        self::assertSame(['first', 'other_first'], $value);
    }
}
```

---

```php
protected function createConfiguration(): AbstractPlatform
```

Override this method if the class test needs the same configuration for all the test cases.

---

```php
protected function createPlatform(): AbstractPlatform
```

Override this method if the class test needs the same platform for all the test cases.

## AbstractEntityManagerTestCase

Provides a base for any test that uses `EntityManagerInterface`. It extends `AbstractDBALTestCase` to be able to create
DBAL connections.

```php
final protected function createEntityManager(
    ?Configuration    $configuration = null,
    ?AbstractPlatform $platform = null,
): TestEntityManager;
```

Creates a new `TestEntityManager` which extends from `Doctrine\ORM\EntityManager`.

It is possible to pass a custom `Doctrine\ORM\Configuration`, otherwise the `createConnection` method will be used.

It is possible to pass a custom `Doctrine\DBAL\Platforms\AbstractPlatform`, otherwise the `createPlatform` method will
be used.

Example:

```php
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

final class MyClassTest extends AbstractEntityManagerTestCase
{
    public function testManager(): void
    {
        $configuration = $this->createConfiguration();
        $configuration->setNamingStrategy(new UnderscoreNamingStrategy());
        $em = $this->createEntityManager($configuration);

        $entity = new MyObject();
        $em->persist($entity);

        self::assertTrue($em->getUnitOfWork()->isScheduledForInsert($entity));
    }

    protected function createConfiguration(): Configuration
    {
        $configuration = parent::createConfiguration();
        $metadata      = $configuration->getMetadataDriverImpl();
        if ($metadata instanceof AttributeDriver) {
            $metadata->addPaths([__DIR__]);
        }

        return $configuration;
    }
}

#[Entity]
final class MyObject
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    public ?int $id = null;
}
```

## AbstractEventSubscriberTestCase

Provides a base for any test that uses `Doctrine\Common\EventSubscriber;`. It extends `AbstractEntityManagerTestCase` to
be able to create entity managers.

```php
abstract protected function createSubscriber(?callable $configure): EventSubscriber;
```

The class test must implement this method and return the subscriber instance. The `configure` closure can be used to
configure any mocked service the subscriber may use.

Depending on the subscriber, it is necessary to create the right event, use one of the following methods:

```php
final protected function createPostLoadEvent(TestEntityManager $manager, object $object): Event\PostLoadEventArgs;
final protected function createLoadClassMetadataEvent(TestEntityManager $manager, ClassMetadata $classMetadata): Event\LoadClassMetadataEventArgs;
final protected function createOnClassMetadataNotFoundEvent(TestEntityManager $manager, string $className): Event\OnClassMetadataNotFoundEventArgs;
final protected function createPrePersistEvent(TestEntityManager $manager, object $object): Event\PrePersistEventArgs;
final protected function createPostPersistEvent(TestEntityManager $manager, object $object): Event\PostPersistEventArgs;
final protected function createPreUpdateEvent(TestEntityManager $manager, object $object, array &$changeSet): Event\PreUpdateEventArgs;
final protected function createPostUpdateEvent(TestEntityManager $manager, object $object): Event\PostUpdateEventArgs;
final protected function createPreRemoveEvent(TestEntityManager $manager, object $object): Event\PreRemoveEventArgs;
final protected function createPostRemoveEvent(TestEntityManager $manager, object $object): Event\PostRemoveEventArgs;
final protected function createPreFlushEvent(TestEntityManager $manager): Event\PreFlushEventArgs;
final protected function createOnFlushEvent(TestEntityManager $manager): Event\OnFlushEventArgs;
final protected function createPostFlushEvent(TestEntityManager $manager): Event\PostFlushEventArgs;
final protected function createOnClearEvent(TestEntityManager $manager): Event\OnClearEventArgs;
```

Example:

```php
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;

final class MyClassTest extends AbstractEventSubscriberTestCase
{
    public function testSubscriber(): void
    {
        $subscriber = $this->createSubscriber(null);
        $manager    = $this->createEntityManager();
        $event      = $this->createOnFlushEvent($manager);

        $subscriber->onFlush($event);

        // ... assertions ...
    }

    protected function createSubscriber(?callable $configure): MySubscriber
    {
        $service = $this->createMock(MyService::class);

        $configure && $configure($service);

        return new MySubscriber($service);
    }
}

final class MySubscriber implements EventSubscriber
{
    public function __construct(
        private readonly MyService $service,
    ) {
    }

    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $event): void
    {
        // ... event logic ...
    }
}
```
