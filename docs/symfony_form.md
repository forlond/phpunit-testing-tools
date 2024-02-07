# Symfony/Form

## Integration

- Use the `TestForm` to perform expectations to any `FormInterface` instance.
- Use the `TestFormErrors` to perform expectations to any `FormErrorIterator` instance.

## TestForm

```php
public function type(Constraint|string $value): self
```

Use the method `type` to indicate the form type.

Example: Assert the form type is an instance of the specified class (the value is transformed in an `IsInstanceOf`
constraint internally)

```php
$test = new TestForm($form);

$test
    ->type(TextType::class)
    ->assert()
;
```

Example: Assert the form type is a resolved type of the specified class.

```php
$test = new TestForm($form);

$test
    ->type(new IsResolvedFormType(TextType::class))
    ->assert()
;
```

---

```php
public function options(Constraint|array $value): self
```

Use the method `options` to indicate how is the form options array.

Example: Assert the form options is exactly the same.

```php
$test = new TestForm($form);

$test
    ->options(['foo' => 'bar'])
    ->assert()
;
```

Example: Assert the form options is an options subset.

```php
// options = ['foo' => 'bar', 'bar' => 'baz']
$test = new TestForm($form);

$test
    ->options(new AssociativeArrayContains(['foo' => 'bar'], false))
    ->assert()
;
```

---

```php
public function option(string $option, mixed $value, mixed $default = null): self
```

Use the method `option` to indicate the value for any option value.

Example: Assert the form option `foo` is exactly the same.

```php
$test = new TestForm($form);

$test
    ->option('foo', 'bar')
    ->assert()
;
```

Example: Assert the form option `foo` matches a constraint.

```php
$test = new TestForm($form);

$test
    ->option('foo', new StringStartsWith('bar'))
    ->assert()
;
```

---

```php
public function required(bool $value): self
```

Use the method `required` to indicate if the form is required or not.

Example: Assert the form is required.

```php
$test = new TestForm($form);

$test
    ->required(true)
    ->assert()
;
```

Example: Assert the form is not required.

```php
$test = new TestForm($form);

$test
    ->required(false)
    ->assert()
;
```

---

```php
public function disabled(bool $value): self
```

Use the method `disabled` to indicate if the form is disabled or not.

Example: Assert the form is disabled.

```php
$test = new TestForm($form);

$test
    ->disabled(true)
    ->assert()
;
```

Example: Assert the form is not disabled.

```php
$test = new TestForm($form);

$test
    ->disabled(false)
    ->assert()
;
```

---

```php
public function isEmpty(bool $value): self
```

Use the method `isEmpty` to indicate if the form is empty or not.

Example: Assert the form is empty.

```php
$test = new TestForm($form);

$test
    ->isEmpty(true)
    ->assert()
;
```

Example: Assert the form is not empty.

```php
$test = new TestForm($form);

$test
    ->isEmpty(false)
    ->assert()
;
```

---

```php
public function propertyPath(Constraint|string $value): self
```

Use the method `propertyPath` to indicate the form property path as string value.

Example: Assert the form property path is equals to string value.

```php
$test = new TestForm($form);

$test
    ->propertyPath('foo')
    ->assert()
;
```

Example: Assert the form property path starts with a string.

```php
$test = new TestForm($form);

$test
    ->propertyPath(new StringStartsWith('foo'))
    ->assert()
;
```

---

```php
public function errors(callable $expect): self
```

Use the method `errors` to perform expectations about the form errors.

> [!NOTE]
> It is not necessary to call `TestFormErrors::assert`, the `TestForm::assert` will do it.

> [!NOTE]
> Child errors are not included. Use the `child` method to make errors expectations about child errors.

The callable will be called with a `TestFormErrors` instance. Read more about `TestFormErrors`.

Example: Assert the form has some errors.

```php
$test = new TestForm($form);

$test
    ->errors(static function(TestFormErrors $errors) {
        $errors
            ->expect('Error message 1')
            ->expect('Error message 2')
        ;
    })
    ->assert()
;
```

---

```php
public function data(mixed $value): self
```

Use the method `data` to indicate the form data.

Example: Assert the form data is exactly the same.

```php
$test = new TestForm($form);

$test
    ->data('foobar')
    ->assert()
;
```

Example: Assert the form data using any PHPUnit constraint.

```php
$test = new TestForm($form);

$test
    ->data(new StringEndsWith('foo'))
    ->assert()
;
```

---

```php
public function normData(mixed $value): self
```

Use the method `normData` to indicate the form data.

Example: Assert the form normData is exactly the same.

```php
$test = new TestForm($form);

$test
    ->normData('foobar')
    ->assert()
;
```

Example: Assert the form normData using any PHPUnit constraint.

```php
$test = new TestForm($form);

$test
    ->normData(new StringEndsWith('foo'))
    ->assert()
;
```

---

```php
public function viewData(mixed $value): self
```

Use the method `viewData` to indicate the form data.

Example: Assert the form viewData is exactly the same.

```php
$test = new TestForm($form);

$test
    ->viewData('foobar')
    ->assert()
;
```

Example: Assert the form viewData using any PHPUnit constraint.

```php
$test = new TestForm($form);

$test
    ->viewData(new StringEndsWith('foo'))
    ->assert()
;
```

---

```php
public function extraData(mixed $value): self
```

Use the method `extraData` to indicate the form data.

Example: Assert the form extraData is exactly the same.

```php
$test = new TestForm($form);

$test
    ->extraData('foobar')
    ->assert()
;
```

Example: Assert the form extraData using any PHPUnit constraint.

```php
$test = new TestForm($form);

$test
    ->extraData(new StringEndsWith('foo'))
    ->assert()
;
```

---

```php
public function valid(bool $value): self
```

Use the method `valid` to indicate if the form is valid or not.

The `valid` method cannot be used if the form was not submitted.

Example: Assert the form is valid.

```php
$test = new TestForm($form);

$test
    ->valid(true)
    ->assert()
;
```

Example: Assert the form is not valid.

```php
$test = new TestForm($form);

$test
    ->valid(false)
    ->assert()
;
```

---

```php
public function submitted(bool $value): self
```

Use the method `submitted` to indicate if the form is submitted or not.

Example: Assert the form is submitted.

```php
$test = new TestForm($form);

$test
    ->submitted(true)
    ->assert()
;
```

Example: Assert the form is not submitted.

```php
$test = new TestForm($form);

$test
    ->submitted(false)
    ->assert()
;
```

---

```php
public function child(string $child, callable|bool $expect): self
```

Use the method `child` to perform expectations about form children.

The callable will be called with a new `TestForm` instance for that child, all the `TestForm` methods ara available.

> [!NOTE]
> It is not necessary to call `TestForm::assert` for the child, the `TestForm::assert` will do it.

> [!NOTE]
> The use of this method will assert about the existence of the child, so if the child does not exist the test will
> fail.

Example: Assert the form has a child called `newChild` which is required and `TextType` type.

```php
$test = new TestForm($form);

$test
    ->child(
        'newChild',
        static function(TestForm $child) {
            $child
                ->type(TextType::class)
                ->required(true)
            ;
        }
    )
    ->assert()
;
```

Example: Assert the form has a child but without any assertion.

```php
$test = new TestForm($form);

$test
    ->child('newChild', true)
    ->assert()
;
```

Example: Assert the form has not a child.

```php
$test = new TestForm($form);

$test
    ->child('newChild', false)
    ->assert()
;
```

---

```php
public function assert(): void
```

Finally, when all the expectations are in place, call the `assert` method.

By default, every child in the form should have its own expectation, otherwise the test will fail. This behaviour can be
disabled by using `disableStrictChildren`. For example, if the form has more children than the expected ones, then the
test will fail.

However, it is possible to disable entirely the children assertion by using `disableChildAssertion` but ensure
the `child` method is not called.

> [!NOTE]
> If you use `TestForm` within a `TestForm::child` expectation, it is not necessary to call `assert` for the child
> expectation, the parent `TestForm::assert` will call it.

## TestFormErrors

```php
public function expect(Constraint|string $message): self
```

Use the method `expect` to indicate the existence of a form error message. The `expect` order invocation is relevant,
but it can be disabled by using the method `disableStrictSequence`.

Example: Assert the form errors have an exact message.

```php
$test = new TestFormErrors($form->getErrors());

$test
    ->expect('Error message')
    ->assert()
;
```

Example: Assert the form errors have a message which start with a string.

```php
$test = new TestFormErrors($form->getErrors());

$test
    ->expect(new StringStartsWith('This is the error with'))
    ->assert()
;
```

---

```php
public function messageTemplate(Constraint|string $value): self
```

Use the method `messageTemplate` to indicate the message template for the current form error.

Example: Assert the error message template.

```php
$test = new TestFormErrors($form->getErrors());

$test
    ->messageTemplate('foo')
    ->assert()
;
```

---

```php
public function parameters(Constraint|array $value): self
```

Use the method `parameters` to indicate the paratemers for the current form error.

Example: Assert the error parameters.

```php
$test = new TestFormErrors($form->getErrors());

$test
    ->parameters(['foo' => 'bar'])
    ->assert()
;
```

---

```php
public function pluralization(Constraint|int $value): self
```

Use the method `pluralization` to indicate the pluralization for the current form error.

Example: Assert the error pluralization.

```php
$test = new TestFormErrors($form->getErrors());

$test
    ->pluralization(1)
    ->assert()
;
```

---

```php
public function cause(mixed $value): self
```

Use the method `cause` to indicate the cause for the current form error.

Example: Assert the error cause.

```php
$test = new TestFormErrors($form->getErrors());

$test
    ->cause('foo')
    ->assert()
;
```

Example: Assert the error cause using a PHPUnit constraint.

```php
$test = new TestFormErrors($form->getErrors());

$test
    ->cause(new StringStartsWith('foo'))
    ->assert()
;
```

---

```php
public function assert(): void
```

Finally, when all the expectations are in place, call the `assert` method. There are two modalities:

In case the number of expectations do not match the number of collected errors, then the entire expection will fail.
This is the default behaviour but it can be disabled by using the `disableStrictSize` method.

> [!NOTE]
> For the non-strict sequence mode when a form error matches a constraint, then that form error is excluded for the
> remaining constraints. The test fails if the same expectation is added more than once.

> [!NOTE]
> When a form error is not found for an expectation, then the test fails.
