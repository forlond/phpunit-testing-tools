# Symfony/Translation

## Integration

Use the `TestTranslator` to perform assertions to any `TranslatorInterface` instance.

## TestTranslator

The `TestTranslator` uses `TranslatorTrait` and implements `LocaleAwareInterface` as well.

The `TestTranslator` can be configured with custom translations in case your logic relies on specific translated values.

```php
$test = new TestTranslator(['foo.bar' => 'baz']);

self::assertSame('baz', $test->trans('foo.bar'));
```

---

```php
public function expect(string $id): self
```

Use the method `expect` to assert that a translation will be made. The `expect` order invocation is relevant,
but it can be disabled by using the method `disableStrictSequence`.

Example: Assert a translation is made.

```php
$test = new TestTranslator();
$test->trans('foo.bar');

$test
    ->expect('foo.bar')
    ->assert()
;
```

---

```php
public function parameters(Constraint|array $parameters): self
```

Use the method `parameters` to indicate how is the parameters array.

Example: Assert the parameters are exactly the same.

```php
$test = new TestTranslator();
$test->trans('foo.bar', ['%count%' => 2]);

$test
    ->expect('foo.bar')
    ->parameters(['%count%' => 2])
    ->assert()
;
```

Example: Assert the parameters are a subset.

```php
$test = new TestTranslator();
$test->trans('foo.bar', ['%count%' => 2, '%total%' => 10]);

$test
    ->expect('foo.bar')
    ->parameters(new ArrayContains(['%total%' => 10], false))
    ->assert()
;
```

---

```php
public function parameter(string $name, mixed $value): self
```

Use the method `parameter` to indicate the value for any parameter value.

Example: Assert the parameter `%count%` is exactly the same.

```php
$test = new TestTranslator();
$test->trans('foo.bar', ['%count%' => 2]);

$test
    ->expect('foo.bar')
    ->parameter('%count%', 2)
    ->assert()
;
```

Example: Assert the parameter `%count%` matches a constraint.

```php
$test = new TestTranslator();
$test->trans('foo.bar', ['%count%' => 2]);

$test
    ->expect('foo.bar')
    ->parameter('%count%', new GreaterThan(1))
    ->assert()
;
```

---

```php
public function domain(Constraint|\Stringable|string $domain): self
```

Use the method `domain` to indicate the expected domain value.

Example: Assert the domain is exactly the same.

```php
$test = new TestTranslator();
$test->trans('foo.bar', [], 'messages');

$test
    ->expect('foo.bar')
    ->domain('messages')
    ->assert()
;
```

Example: Assert the domain matches a constraint.

```php
$test = new TestTranslator();
$test->trans('foo.bar', [], 'messages');

$test
    ->expect('foo.bar')
    ->domain(new StringStartsWith('mess'))
    ->assert()
;
```

---

```php
public function locale(Constraint|\Stringable|string $locale): self
```

Use the method `locale` to indicate the expected locale value.

Example: Assert the locale is exactly the same.

```php
$test = new TestTranslator();
$test->trans('foo.bar', [], 'messages', 'es');

$test
    ->expect('foo.bar')
    ->locale('es')
    ->assert()
;
```

Example: Assert the locale matches a constraint.

```php
$test = new TestTranslator();
$test->trans('foo.bar', [], 'messages', 'es');

$test
    ->expect('foo.bar')
    ->locale(new StringStartsWith('e'))
    ->assert()
;
```

---

```php
public function assert(): void
```

Finally, when all the assertions are in place, call the `assert` method.

In case the number of assertions do not match the number of translations, then the test will fail.
This is the default behaviour, but it can be disabled by using the `disableStrictSize` method.

> [!NOTE]
> For the non-strict sequence mode when a translation matches an assertion, then that assertion is not considered
> again for the remaining translations.

> [!NOTE]
> When a translation is not found for an assertion, then the test fails.
