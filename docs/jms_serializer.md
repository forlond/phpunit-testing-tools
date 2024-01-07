# JMS/Serializer

## Integration

Use one of the following abstract test cases:

- `AbstractSerializerTestCase` for general serializer purposes.
- `AbstractEventSubscriberTestCase` for `EventSubscriberInterface` implementations.
- `AbstractSubscribingHandlerTestCase` for `SubscribingHandlerInterface` implementations.
- `AbstractObjectConstructorTestCase` for `ObjectConstructorInterface` implementations.

## AbstractSerializerTestCase

Provides a base for any test that uses the serializer.

### Methods

```php
protected function createSerializer(?callable $configure): Serializer;
```

Allows to create a new `Serializer` instance. The instance can be configured by passing a closure.

If no closure is passed, then the serializer is built with the default settings.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $serializer = $this->createSerializer(static function(SerializerBuilder $builder) {
            $builder->enableEnumSupport(true);
        });

        // ...
    }
}
```

---

```php
final protected function parseType(string $type): array
```

Allows to return well-formed types in an array format.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        // ['name' => 'array', 'params' => [['name' => 'MyClass', 'params' => []]]
        $type = $this->parseType('array<MyClass>');

        // ...
    }
}
```

---

```php
protected function createSerializationContext(?callable $configure): SerializationContext;
protected function createDeserializationContext(?callable $configure): DeserializationContext
```

Allows to create a new `SerializationContext` or `DeserializationContext` instance. The context can be decorated before
its initialization by passing a closure.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFoo(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            $factory->format = 'json';
            $factory->context->setAttribute('foo', 'bar');
        });

        // ...
    }

    public function testBar(): void
    {
        $context = $this->createDeserializationContext(static function(TestDeserializationContextFactory $factory) {
            $factory->format = 'json';
            $factory->context->setAttribute('foo', 'bar');
        });

        // ...
    }
}
```

### TestSerializationContextFactory/TestDeserializationContextFactory

Allows to configure the `format` and the factories for the `visitor`, `graph navigator` and `metadata` by updating its
public properties.

> [!NOTE]
> The default `format` is `json`

> [!NOTE]
> The default `GraphNavigatorFactoryInterface` for the serialization context is `TestSerializationGraphNavigatorFactory`
> that creates a configurable `SerializationGraphNavigator`.

> [!NOTE]
> The default `GraphNavigatorFactoryInterface` for the deserialization context
> is `TestDeserializationGraphNavigatorFactory` that creates a configurable `DeserializationGraphNavigator`.

> [!NOTE]
> The default `metadata` factory is the`MetadataFactory` implementation with the `AttributeDriver` driver and
> the `IdenticalPropertyNamingStrategy` strategy name.

#### Configure the Format

The format is a scalar string value, and it is defaulted to `json`.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            $factory->format = 'xml';
        });

        // ...
    }
}
```

#### Configure the Metadata factory

Set a new `MetadataFactoryInterface` instance in the `metadataFactory` property.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            $factory->metadataFactory = new MetadataFactory(new MyCustomDriver(), ClassHierarchyMetadata::class);
        });

        // ...
    }
}
```

#### Configure the Visitor factories

New `SerializationVisitorFactory` and/or `DeserializationVisitorFactory` visitors can be registered for certain formats.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            $factory->format = 'csv';
            $factory->setVisitorFactory('csv', new CSVSerializationVisitorFactory())
        });

        // ...
    }
}
```

In case there is no factory for the visitor, the `TestSerializationVisitorFactory` can be used.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            $factory->format = 'csv';
            $factory->setVisitorFactory(
                'csv',
                new TestSerializationVisitorFactory(static fn() => new CSVSerializationVisitor())
            )
        });

        // ...
    }
}
```

However, the JMS visitor factories for `json` and `xml` are already registered before the closure for customization is
called. If the visitor allows it, the `SerializationVisitorInterface` and/or `DeserializationVisitorInterface` can be
configured.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            /** @var JsonSerializationVisitorFactory $visitorFactory */
            $visitorFactory = $factory->getVisitorFactory('json');
            $visitorFactory->setOptions(JSON_ERROR_NONE);
        });

        // ...
    }
}
```

#### Configure the Graph Navigator factory

A new `GraphNavigatorFactoryInterface` can be set by updating the public `graphNavigatorFactory` property.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            $factory->graphNavigatorFactory = new MyGraphNavigatorFactory();
        });

        // ...
    }
}
```

In case there is no factory for the graph navigator, the `TestGraphNavigatorFactory` can be used.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            $factory->graphNavigatorFactory = new TestGraphNavigatorFactory(static fn() => new MyGraphNavigator());
        });

        // ...
    }
}
```

However, some configurable factories are set initially.

For serialization contexts, the `TestSerializationGraphNavigatorFactory` is initially set as the default factory. This
factory creates a `SerializationGraphNavigator` instance and allows to configure this navigator instance in the
following manner:

- Adding handlers to the `HandlerRegistryInterface` instance (by default is `HandlerRegistry`)
- Changing the `AccessorStrategyInterface` instance (by default is `DefaultAccessorStrategy`)
- Setting a `EventDispatcherInterface` instance (by default none is set)
- Setting a `ExpressionEvaluatorInterface` instance (by default none is set)

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            /** @var TestSerializationGraphNavigatorFactory $navigatorFactory */
            $navigatorFactory = $factory->graphNavigatorFactory;
            $navigatorFactory->handlerRegistry->registerSubscribingHandler(new MyCustomHandler());
            $navigatorFactory->accessor            = new MyCustomAccessorStrategy();
            $navigatorFactory->dispatcher          = new MyCustomEventDispatcher();
            $navigatorFactory->expressionEvaluator = new MyCustomExpressionEvaluator();
        });

        // ...
    }
}
```

For deserialization contexts, the `TestDeserializationGraphNavigatorFactory` is initially set as the default factory.
This factory creates a `DeserializationGraphNavigator` instance and allows to configure this navigator instance in the
following manner:

- Adding handlers to the `HandlerRegistryInterface` instance (by default is `HandlerRegistry`)
- Changing the `AccessorStrategyInterface` instance (by default is `DefaultAccessorStrategy`)
- Changing the `ObjectConstructorInterface` instance (by default is `UnserializeObjectConstructor`)
- Setting a `EventDispatcherInterface` instance (by default none is set)
- Setting a `ExpressionEvaluatorInterface` instance (by default none is set)

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createDeserializationContext(static function(TestDeserializationContextFactory $factory) {
            /** @var TestDeserializationGraphNavigatorFactory $graphFactory */
            $graphFactory = $factory->graphNavigatorFactory;
            $graphFactory->handlerRegistry->registerSubscribingHandler(new MyCustomHandler());
            $graphFactory->accessor            = new MyCustomAccessorStrategy();
            $graphFactory->objectConstructor   = new MyObjectConstructor();
            $graphFactory->dispatcher          = new MyCustomEventDispatcher();
            $graphFactory->expressionEvaluator = new MyCustomExpressionEvaluator();
        });

        // ...
    }
}
```

> [!NOTE]
> Depending on the context a correct `GraphNavigatorInterface` instance is necessary.

> [!NOTE]
> The `metadataFactory` property is for internal use and it will be replaced by the test case.

#### Initial Graph Navigation

If the test requires an initial navigation, this can be done with the `pushInitialGraph` method. This is useful if
the logic depends on some context's properties such as the _depth_, _metadata_, etc.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            $factory->pushInitialGraph(new TestAClass(), 'propertyOne');
            $factory->pushInitialGraph(new TestBClass(), 'propertyTwo');
            // current path will be propertyOne.propertyTwo
        });

        // ...
    }
}
```

## AbstractEventSubscriberTestCase

Allows to unit test `EventSubscriberInterface` instances. It extends `AbstractSerializerTestCase` to be able to create
contexts instances. The test must implement the following method.

```php
abstract protected function createSubscriber(?callable $configure): EventSubscriberInterface;
```

The `configure` closure can be used to configure any mocked service the event subscriber may use.

### Methods

In order to create the correct event instance, use one of the following methods:

```php
protected function createPreSerializeEvent(SerializationContext $context, object $object, ?string $type = null): PreSerializeEvent;
protected function createPreDeserializeEvent(DeserializationContext $context, mixed $data, string $type): PreDeserializeEvent;
protected function createPostSerializeEvent(SerializationContext $context, object $object, ?string $type = null): ObjectEvent;
protected function createPostDeserializeEvent(DeserializationContext $context, object $object, ?string $type = null): ObjectEvent;
```

For some events, and after the subscriber execution, it is possible to the get the data _result_.

```php
protected function getEventResult(Event $event): mixed;
```

**Example**

```php
final class MyTestEvent extends AbstractEventSubscriberTestCase
{
    public function testFooBar(): void
    {
        $object     = new \stdClass();
        $context    = $this->createSerializationContext(static function(TestSerializationContextFactory $factory) {
            $factory->pushInitialGraph(new TestClass(), 'property');
        });
        $event      = $this->createPostSerializeEvent($context, $object);
        $subscriber = $this->createSubscriber(static function(MockObject $service) {
            $service
                ->expect(self::once())
                ->method('methodName')
                ->willReturn(true)
            ;
        });

        // The logic adds a foo property with bar value.
        $subscriber->onPostSerialize($event);

        $data = $this->getEventResult($event);

        self::assertSame(['foo' => 'bar'], $data);
    }

    protected function createSubscriber(?callable $configure): MyEventSubscriber
    {
        $service = $this->createMock(ServiceInterface::class);

        $configure && $configure($service);

        return new MyEventSubscriber($service);
    }
}
```

## AbstractSubscribingHandlerTestCase

Allows to unit test `SubscribingHandlerInterface` instances. It extends `AbstractSerializerTestCase` to be able to
create contexts instances. The test must implement the following method.

```php
abstract protected function createHandler(?callable $configure): SubscribingHandlerInterface;
```

The `configure` closure can be used to configure any mocked service the event subscriber may use.

**Example**

```php
final class MyTestEvent extends AbstractSubscribingHandlerTestCase
{
    public function testFooBar(): void
    {
        $object  = new \stdClass();
        $context = $this->createSerializationContext(null);
        $handler = $this->createHandler(static function(MockObject $service) {
            $service
                ->expect(self::once())
                ->method('methodName')
                ->willReturn(true)
            ;
        });

        // The logic returns an associative array with the id property only.
        $result = $handler->serializeObject($context->getVisitor(), $object, ['name' => 'stdClass'], $context);

        self::assertSame(['id' => null], $result);
    }

    protected function createHandler(?callable $configure): MySubscribingHandler
    {
        $service = $this->createMock(ServiceInterface::class);

        $configure && $configure($service);

        return new MySubscribingHandler($service);
    }
}
```

## AbstractObjectConstructorTestCase

Allows to unit test `ObjectConstructorInterface` instances. It extends `AbstractSerializerTestCase` to be able to
create contexts instances. The test must implement the following method.

```php
abstract protected function createConstructor(?callable $configure): ObjectConstructorInterface;
```

The `configure` closure can be used to configure any mocked service the event subscriber may use.

**Example**

```php
final class MyTestEvent extends AbstractObjectConstructorTestCase
{
    public function testFooBar(): void
    {
        $object      = new \stdClass();
        $context     = $this->createDeserializationContext(null);
        $constructor = $this->createConstructor(static function(MockObject $service) {
            $service
                ->expect(self::once())
                ->method('methodName')
                ->willReturn(true)
            ;
        });

        $result = $constructor->construct(
            $context->getVisitor(),
            $metadata,
            ['id' => 5],
            ['name' => 'stdClass'],
            $context
        );

        self::assertInstanceOf(\stdClass::class, $result);
    }

    protected function createConstructor(?callable $configure): ObjectConstructorInterface
    {
        $service = $this->createMock(ServiceInterface::class);

        $configure && $configure($service);

        return new MyObjectConstructor($service);
    }
}
```
