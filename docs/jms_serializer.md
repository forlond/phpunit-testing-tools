# JMS/Serializer

## Integration

Use one of the following abstract test cases for your custom JMS implementations:

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
        $context = $this->createSerializationContext(
            static function(TestSerializerConfigurator $configurator, TestSerializationContext $context) {
                $configurator->format = 'json';
                $context->setAttribute('foo', 'bar');
            }
        );

        // ...
    }
    
    public function testBar(): void
    {
        $context = $this->createDeserializationContext(
            static function(TestSerializerConfigurator $configurator, TestDeserializationContext $context) {
                $configurator->format = 'json';
                $context->setAttribute('foo', 'bar');
            }
        );

        // ...
    }
}
```

### TestSerializerConfigurator

Allows to configure the format, visitor, graph navigator and metadata factory by updating its public properties.

- `format`, by default is `json`
- `visitor`, the `VisitorInterface` instance. If no value is set, then the test case will set the correct
  out-of-the-box visitor implementation based on the format.
- `navigator`, the `GraphNavigatorInterface` instance. If no value is set, then the test case will set the correct
  out-of-the-box graph navigator based on the serialization direction.
- `metadataFactory`, the `MetadataFactoryInterface` instance. If no value is set, then the test case will set the JMS
  `MetadataFactory` implementation with the `AttributeDriver` driver and the `IdenticalPropertyNamingStrategy` strategy
  name.

#### Configuring visitors

The `TestSerializerConfigurator` can be configured with different visitor factories. By default, the `json` and `xml`
visitor factories are already registered before you can do some other changes in the closure.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(
            static function(TestSerializerConfigurator $configurator, TestSerializationContext $context) {
                $configurator->format = 'json';
                $factory = $configurator->getVisitorFactory(); // returns JsonSerializationVisitorFactory
                $factory->setOptions(JSON_ERROR_NONE);
            }
        );

        // ...
    }
}
```

Also, your custom visitor implementations can be added:

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(
            static function(TestSerializerConfigurator $configurator, TestSerializationContext $context) {
                $configurator->format = 'csv';
                $configurator->setVisitorFactory('csv', new CSVSerializationVisitorFactory())
            }
        );

        // ...
    }
}
```

Finally, you can replicate some navigation state with the `pushInitialVisiting` method. This is useful if your logic
depends on the context _depth_, _metadata_, etc.

```php
final class MyTestEvent extends AbstractSerializerTestCase
{
    public function testFooBar(): void
    {
        $context = $this->createSerializationContext(
            static function(TestSerializerConfigurator $configurator, TestSerializationContext $context) {
                $configurator->pushInitialVisiting(new TestClass(), 'property');
            }
        );

        // ...
    }
}
```

## AbstractEventSubscriberTestCase

Allows to unit test `EventSubscriberInterface` instances. It extends `AbstractSerializerTestCase` to be able to create
contexts instances.  The test must implement the following method.

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

For some events, and after the event execution, it is possible to the get the data _result_.

```php
final protected function getEventResult(Event $event);
```

#### Example

```php
final class MyTestEvent extends AbstractEventSubscriberTestCase
{
    public function testFooBar(): void
    {
        $object     = new \stdClass();
        $context    = $this->createSerializationContext(
            static function(TestSerializerConfigurator $configurator, TestDeserializationContext $context) {
                $configurator->pushInitialVisiting(new TestClass(), 'property');
            }
        );
        $event      = $this->createPostSerializeEvent($context, $object);
        $subscriber = $this->createSubscriber(function(MockObject $service) {
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

#### Example

```php
final class MyTestEvent extends AbstractSubscribingHandlerTestCase
{
    public function testFooBar(): void
    {
        $object  = new \stdClass();
        $context = $this->createSerializationContext(null);
        $handler = $this->createHandler(function(MockObject $service) {
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

#### Example

```php
final class MyTestEvent extends AbstractObjectConstructorTestCase
{
    public function testFooBar(): void
    {
        $object      = new \stdClass();
        $context     = $this->createDeserializationContext(null);
        $constructor = $this->createConstructor(function(MockObject $service) {
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
