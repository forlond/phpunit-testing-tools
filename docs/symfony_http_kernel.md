# Symfony/HttpKernel

## Integration

- Use the `TestHttpKernelTrait` to create kernel event instances.
- Use the `TestHttpKernel` to mock any `HttpKernelInterface` instance.

## TestHttpKernel

The `TestHttpKernel` implements the `HttpKernelInterface` interface.

This implementation allows to configure the following properties

- The `Request` instance.
- The `Response` instance.
- The request type (main or sub request)

The `handle` method will return the configured `Response` object, if no response is set, then an empty response is
returned instead.

> [!NOTE]
> The request passed to the `handle` method may be a different object instance. The `request` property is a helper to
> configure the request instance for the kernel events.

## TestHttpKernelTrait

---

```php
protected function createRequestEvent(?callable $configure): RequestEvent
```

Creates a `RequestEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestHttpKernel` that can be updated with a custom `Request` and/or a request type.

```php
$event = $this->createRequestEvent(static function(TestHttpKernel $kernel) {
    $kernel->type    = HttpKernelInterface::SUB_REQUEST;
    $kernel->request = new Request('/path');
});
```

---

```php
protected function createLazyResponseEvent(RequestEvent $event): LazyResponseEvent
```

Creates a `LazyResponseEvent` object.

```php
$event = $this->createLazyResponseEvent(
    $this->createRequestEvent(static function(TestHttpKernel $kernel) {
        $kernel->request = new Request('/path');
    })
);
```

---

```php
protected function createControllerEvent(?callable $configure, ?callable $controller = null): ControllerEvent
```

Creates a `ControllerEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestHttpKernel` that can be updated with a custom `Request` and/or a request type.

Also, it is possible to pass a `$controller` closure, a noop closure is used if no controller is passed.

```php
$event = $this->createControllerEvent(static function(TestHttpKernel $kernel) {
    $kernel->request = new Request('/path');
});
```

```php
$event = $this->createControllerEvent(
    static function(TestHttpKernel $kernel) {
        $kernel->request = new Request('/path');
    },
    static function() {
        return new Response();
    }
);
```

---

```php
protected function createControllerArgumentsEvent(
    array $arguments,
    ?ControllerEvent $event = null
): ControllerArgumentsEvent
```

Creates a `ControllerArgumentsEvent` object. It is necessary to pass the list of controller arguments.

The `ControllerEvent` argument is optional.

```php
$event = $this->createControllerArgumentsEvent([1, 2]);
```

```php
$event = $this->createControllerArgumentsEvent(
    [1, 2],
    $this->createControllerEvent(static function(TestHttpKernel $kernel) {
        $kernel->request = new Request('/path');
    })
);
```

---

```php
protected function createResponseEvent(?callable $configure): ResponseEvent
```

Creates a `ResponseEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestHttpKernel` that can be updated with a custom `Request`, request type and/or a `Response`.

```php
$event = $this->createResponseEvent(static function(TestHttpKernel $kernel) {
    $kernel->request  = new Request('/path');
    $kernel->response = new Response('');
});
```

---

```php
protected function createViewEvent(
    ?callable $configure,
    mixed $controllerResult = null,
    ?ControllerArgumentsEvent $event = null,
): ViewEvent
```

Creates a `ViewEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestHttpKernel` that can be updated with a custom `Request` and/or request type.

Also, it is possible to pass a `$controllerResult` value, a null value is used if no controller result is passed.

The `ControllerArgumentsEvent` argument is optional.

```php
$event = $this->createViewEvent(static function(TestHttpKernel $kernel) use ($request) {
    $kernel->request = new Request('/path');
});
```

```php
$event = $this->createViewEvent(
    static function(TestHttpKernel $kernel) use ($request) {
        $kernel->request = new Request('/path');
    },
    ['var' => 'value']
);
```

```php
$request = new Request('/path');
$event = $this->createViewEvent(
    static function(TestHttpKernel $kernel) use ($request) {
        $kernel->request = $request;
    },
    ['var' => 'value'],
    $this->createControllerArgumentsEvent(static function(TestHttpKernel $kernel) use ($request) {
        $kernel->request = $request;
    })
);
```

---

```php
protected function createFinishRequest(?callable $configure): FinishRequestEvent
```

Creates a `FinishRequestEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestHttpKernel` that can be updated with a custom `Request` and/or request type.

```php
$event = $this->createFinishRequest(static function(TestHttpKernel $kernel) use ($request) {
    $kernel->request = new Request('/path');
});
```

---

```php
protected function createTerminateEvent(?callable $configure): TerminateEvent
```

Creates a `TerminateEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestHttpKernel` that can be updated with a custom `Request` and/or `Response`.

```php
$event = $this->createTerminateEvent(static function(TestHttpKernel $kernel) use ($request) {
    $kernel->request  = new Request('/path');
    $kernel->response = new Response('');
});
```

---

```php
protected function createExceptionEvent(?callable $configure, ?\Throwable $exception = null): ExceptionEvent
```

Creates an `ExceptionEvent` object. The event can be configured by using the `$configure` closure.

The closure gets a `TestHttpKernel` that can be updated with a custom `Request` and/or request type.

Also, it is possible to pass a throwable `$exception`, an `\Exception` instance is used if no throwable is passed.

```php
$event = $this->createExceptionEvent(
    static function(TestHttpKernel $kernel) use ($request) {
        $kernel->request = new Request('/path');
        $kernel->response = new Response('');
    },
    new \LogicException('Error')
);
```
