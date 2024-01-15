# Doctrine/ORM

## Integration

Use one of the following abstract test cases:

- `AbstractDBALTestCase` for general DBAL purposes.
- `AbstractEntityManagerTestCase` for general ORM purposes.
- `AbstractEventSubscriberTestCase` for `EventSubscriber` implementations.

## AbstractDBALTestCase

Provides a base for any test that uses DBAL connection, drivers, etc.

### Methods

```php
final protected function createConnection(?AbstractPlatform $platform = null): TestDBALConnection;
```

Allows to create a new `Connection` DBAL instance.

It is possible to pass a specific platform, otherwise the defined class platform will be used.

---

```php
protected function getPlatform(): AbstractPlatform
```

Allows to change the default platform instance for the

## AbstractEntityManagerTestCase

Provides a base for any test that uses `EntityManagerInterface`. It extends `AbstractDBALTestCase` to be able to create
a DBAL test connection.

### Methods

```php
final protected function createEntityManager(?callable $configure, ?AbstractPlatform $platform = null): TestEntityManager
```

Allows to create a new `EntityManagerInterface` instance.

## AbstractEventSubscriberTestCase

Allows to unit test `EventSubscriber` instances. It extends `AbstractEntityManagerTestCase` to be able to create
the available Doctrine events. The test must implement the following method.

```php
abstract protected function createSubscriber(?callable $configure): EventSubscriber;
```

The `configure` closure can be used to configure any mocked service the event subscriber may use.

### Methods

In order to create the correct event instance, use one of the following methods:

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
