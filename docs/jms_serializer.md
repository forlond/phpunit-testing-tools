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
protected function createSerializationContext(?callable $configure): TestSerializationContext;
protected function createDeserializationContext(?callable $configure): TestDeserializationContext
```

Allows to create a new `SerializationContext` or `DeserializationContext` instance. The context can be decorated before
its initialization by passing a closure.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFoo(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            $configurator->format = 'json';
            $configurator->context->setAttribute('foo', 'bar');
        });

        // ...
    }
    
    public function testBar(): void
    {
        $context = $this->createDeserializationContext(static function(TestSerializerConfigurator $configurator) {
            $configurator->format = 'json';
            $configurator->context->setAttribute('foo', 'bar');
        });

        // ...
    }
}
```

### TestSerializerConfigurator

Allows to configure the `format` and the factories for the `visitor`, `graph navigator` and `metadata` by updating its
public properties.

Notes:

- The default `format` is `json`
- The default `GraphNavigatorFactoryInterface` for the serialization context is `TestSerializationGraphNavigatorFactory`
  that creates a configurable `SerializationGraphNavigator`.
- The default `GraphNavigatorFactoryInterface` for the deserialization context
  is `TestDeserializationGraphNavigatorFactory` that creates a configurable `DeserializationGraphNavigator`.
- The default `metadata` factory is the`MetadataFactory` implementation with the `AttributeDriver` driver and
  the `IdenticalPropertyNamingStrategy` strategy name.

#### Configure the Format

The format is a scalar string value, and it is defaulted to `json`.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            $configurator->format = 'xml';
        });

        // ...
    }
}
```

Note: The format is important when using the `TestSerializerConfigurator::getVisitorFactory` method.

#### Configure the Metadata factory

Set a new `MetadataFactoryInterface` instance in the `metadataFactory` property.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            $configurator->metadataFactory = new MetadataFactory(new MyCustomDriver(), ClassHierarchyMetadata::class);
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
        $context = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            $configurator->format = 'csv';
            $configurator->setVisitorFactory('csv', new CSVSerializationVisitorFactory())
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
        $context = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            $configurator->format = 'csv';
            $configurator->setVisitorFactory(
                'csv',
                new TestSerializationVisitorFactory(static fn() => new CSVSerializationVisitor())
            )
        });

        // ...
    }
}
```

However, the JMS visitor factories for `json` and `xml` are registered before the closure for customization is called.
If the visitor allows it, the `SerializationVisitorInterface` and/or `DeserializationVisitorInterface` can be
configured.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            /** @var JsonSerializationVisitorFactory $factory */
            $factory = $configurator->getVisitorFactory();
            $factory->setOptions(JSON_ERROR_NONE);
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
        $context = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            $configurator->graphNavigatorFactory = new MyGraphNavigatorFactory();
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
        $context = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            $configurator->graphNavigatorFactory = new TestGraphNavigatorFactory(static function() {
                return new MyGraphNavigator();
            });
        });

        // ...
    }
}
```

However, some configurable factories are set initially.

For serialization contexts, the `TestSerializationGraphNavigatorFactory` is initially set as default factory. This
factory creates `SerializationGraphNavigator` and allows to configure this navigator instance in the following manner:

- Adding handlers to the `HandlerRegistryInterface` instance (by default is `HandlerRegistry`)
- Changing the `AccessorStrategyInterface` instance (by default is `DefaultAccessorStrategy`)
- Setting a `EventDispatcherInterface` instance (by default none is set)
- Setting a `ExpressionEvaluatorInterface` instance (by default none is set)

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            /** @var TestSerializationGraphNavigatorFactory $factory */
            $factory = $configurator->graphNavigatorFactory;
            $factory->handlerRegistry->registerSubscribingHandler(new MyCustomHandler());
            $factory->accessor            = new MyCustomAccessorStrategy();
            $factory->dispatcher          = new MyCustomEventDispatcher();
            $factory->expressionEvaluator = new MyCustomExpressionEvaluator();
        });

        // ...
    }
}
```

For deserialization contexts, the `TestDeserializationGraphNavigatorFactory` is initially set as default factory. This
factory creates `DeserializationGraphNavigator` and allows to configure this navigator instance in the following manner:

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
        $context = $this->createDeserializationContext(static function(TestSerializerConfigurator $configurator) {
            /** @var TestDeserializationGraphNavigatorFactory $factory */
            $factory = $configurator->graphNavigatorFactory;
            $factory->handlerRegistry->registerSubscribingHandler(new MyCustomHandler());
            $factory->accessor            = new MyCustomAccessorStrategy();
            $factory->objectConstructor   = new MyObjectConstructor();
            $factory->dispatcher          = new MyCustomEventDispatcher();
            $factory->expressionEvaluator = new MyCustomExpressionEvaluator();
        });

        // ...
    }
}
```

Notes:

- Depending on the context a correct `GraphNavigatorInterface` instance is necessary.
- The `metadataFactory` property is for internal use and it will be replaced by the test case.

#### Simulate Navigation

If the test requires an initial navigation, this can be done with the `pushInitialVisiting` method. This is useful if
the logic depends on some context's properties such as the _depth_, _metadata_, etc.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            $configurator->pushInitialVisiting(new TestAClass(), 'propertyOne');
            $configurator->pushInitialVisiting(new TestBClass(), 'propertyTwo');
            // current path will be propertyOne.propertyTwo with the class and property metadatas
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
final protected function createPreSerializeEvent(TestSerializationContext $context, object $object): PreSerializeEvent;
final protected function createPreDeserializeEvent(TestDeserializationContext $context, mixed $data, string $type): PreDeserializeEvent;
final protected function createPostSerializeEvent(TestSerializationContext $context, object $object): ObjectEvent;
final protected function createPostDeserializeEvent(TestDeserializationContext $context, object $object): ObjectEvent;
```

For some events, and after the subscriber execution, it is possible to the get the data _result_.

```php
final protected function getEventResult(Event $event);
```

**Example**

```php
final class MyTestEvent extends AbstractEventSubscriberTestCase
{
    public function testFooBar(): void
    {
        $object     = new \stdClass();
        $context    = $this->createSerializationContext(static function(TestSerializerConfigurator $configurator) {
            $configurator->pushInitialVisiting(new TestClass(), 'property');
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
