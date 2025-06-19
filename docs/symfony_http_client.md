# Symfony/HttpClient

## Integration

Use the `TestHttpClient` to perform assertions to any `HttpClientInterface` instance.

## TestHttpClient

The `TestHttpClient` uses `MockHttpClient` internally, so the assertions will be made against `MockResponse` instances.

```php
public function expect(string $method, Constraint|\Stringable|string $uri): self
```

Use the method `expect` to assert that a request will be sent. The `expect` order invocation is relevant,
but it can be disabled by using the method `disableStrictSequence`.

Example: Assert a request is sent.

```php
$test = new TestHttpClient();
$test->request('GET', '/path');

$test
    ->expect('GET', 'https://example.com/path')
    ->assert()
;
```

---

```php
public function options(Constraint|array $options): self
```

Use the method `options` to indicate how is the request options array.

> [!NOTE]
> The `MockHttpClient` adds additional request options.


Example: Assert the request options are exactly the same.

```php
$test = new TestHttpClient();
$test->request('GET', '/path', ['foo' => 'bar']);

$test
    ->expect('GET', 'https://example.com/path')
    ->options([
        'foo'                => 'bar',
        'normalized_headers' => ['accept' => ['Accept: */*']],
        'headers'            => ['Accept: */*'],
        'query'              => [],
        'base_uri'           => [
            'scheme'    => 'https:',
            'authority' => '//example.com',
            'path'      => null,
            'query'     => null,
            'fragment'  => null,
        ],
        'http_version'       => null,
        'max_duration'       => 0,
    ])
    ->assert()
;
```

Example: Assert the request options are an options subset.

```php
$test = new TestHttpClient();
$test->request('GET', '/path', ['foo' => 'bar', 'bar' => 'baz']);

$test
    ->expect('GET', 'https://example.com/path')
    ->options(new ArrayContains(['foo' => 'bar'], false))
    ->assert()
;
```

---

```php
public function option(string $name, mixed $value): self
```

Use the method `option` to indicate the value for any request option value.

Example: Assert the request option `foo` is exactly the same.

```php
$test = new TestHttpClient();
$test->request('GET', '/path', ['foo' => 'bar', 'bar' => 'baz']);

$test
    ->expect('GET', 'https://example.com/path')
    ->option('foo', 'bar')
    ->assert()
;
```

Example: Assert the form option `foo` matches a constraint.

```php
$test = new TestHttpClient();
$test->request('GET', '/path', ['foo' => 'bar', 'bar' => 'baz']);

$test
    ->expect('GET', 'https://example.com/path')
    ->option('foo', new StringStartsWith('bar'))
    ->assert()
;
```

---

```php
public function assert(): void
```

Finally, when all the assertions are in place, call the `assert` method.

In case the number of assertions do not match the number of requests, then the test will fail.
This is the default behaviour, but it can be disabled by using the `disableStrictSize` method.

> [!NOTE]
> For the non-strict sequence mode when a request matches an assertion, then that assertion is not considered
> again for the remaining requests.

> [!NOTE]
> When a request is not found for an assertion, then the test fails.
