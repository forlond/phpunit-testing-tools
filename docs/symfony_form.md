# Symfony/Form

## Integration

Use the `TestForm` to perform expectations to any `FormInterface` instance.
Use the `TestFormErrors` to perform expectations to any `FormErrorIterator` instance.

## TestForm

### type

Use the method `type` to indicate the form type.

```php
public function type(Constraint|string $value): self
```

Example: Assert the form type is an instance of the specified class (internally value is transformed in
an `IsInstanceOf` constraint)

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

### options

Use the method `options` to indicate how is the form options array.

```php
public function options(Constraint|array $value): self
```

Example: Assert the form options is exactly the same.

```php
$test = new TestForm($form);

$test
    ->options(['foo' => 'bar'])
    ->assert()
;
```

Example: Assert the form options is a subset.

```php
// options = ['foo' => 'bar', 'bar' => 'baz']
$test = new TestForm($form);

$test
    ->type(new AssociativeArrayContains(['foo' => 'bar'], false))
    ->assert()
;
```

### required

Use the method `required` to indicate if the form is required or not.

```php
public function required(bool $value): self
```

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

### disabled

Use the method `disabled` to indicate if the form is disabled or not.

```php
public function disabled(bool $value): self
```

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

### isEmpty

Use the method `isEmpty` to indicate if the form is empty or not.

```php
public function isEmpty(bool $value): self
```

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

Finally, when all the expectations are in place, the `assert` method needs be used. There are two modalities:

```php
public function assert(bool $strict = true): void
```

- When the `strict` mode is `true`, the number of expected logs must match the number of collected log messages.
- When the `strict` mode is `false`, only the expected logs will be checked against the collected log messages,
  regardless of any unchecked log messages.

The default behaviour for the `strict` mode is `true`.

Notes:

- When a log message matches something expected, that log message is not considered again for the remaining
  expectations. The test fails in that case.
- When a log message is not found for an expectation, then the test fails.

### propertyPath

Use the method `propertyPath` to indicate the form property path as string value.

```php
public function propertyPath(Constraint|string $value): self
```

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

### errors

Use the method `errors` to perform expectations about the form errors.

Note: Child errors are not included. Use the `child` method to make errors expectations about child errors.

The callable will be called with a `TestFormErrors` instance. Read more about `TestFormErrors`.

```php
public function errors(callable $expect): self
```

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

### data

Use the method `data` to indicate the form data.

```php
public function data(mixed $value): self
```

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

Finally, when all the expectations are in place, the `assert` method needs be used. There are two modalities:

```php
public function assert(bool $strict = true): void
```

- When the `strict` mode is `true`, the number of expected logs must match the number of collected log messages.
- When the `strict` mode is `false`, only the expected logs will be checked against the collected log messages,
  regardless of any unchecked log messages.

The default behaviour for the `strict` mode is `true`.

Notes:

- When a log message matches something expected, that log message is not considered again for the remaining
  expectations. The test fails in that case.
- When a log message is not found for an expectation, then the test fails.

### normData

Use the method `normData` to indicate the form data.

```php
public function normData(mixed $value): self
```

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

### viewData

Use the method `viewData` to indicate the form data.

```php
public function viewData(mixed $value): self
```

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

Finally, when all the expectations are in place, the `assert` method needs be used. There are two modalities:

```php
public function assert(bool $strict = true): void
```

- When the `strict` mode is `true`, the number of expected logs must match the number of collected log messages.
- When the `strict` mode is `false`, only the expected logs will be checked against the collected log messages,
  regardless of any unchecked log messages.

The default behaviour for the `strict` mode is `true`.

Notes:

- When a log message matches something expected, that log message is not considered again for the remaining
  expectations. The test fails in that case.
- When a log message is not found for an expectation, then the test fails.

### extraData

Use the method `extraData` to indicate the form data.

```php
public function extraData(mixed $value): self
```

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

Finally, when all the expectations are in place, the `assert` method needs be used. There are two modalities:

```php
public function assert(bool $strict = true): void
```

- When the `strict` mode is `true`, the number of expected logs must match the number of collected log messages.
- When the `strict` mode is `false`, only the expected logs will be checked against the collected log messages,
  regardless of any unchecked log messages.

The default behaviour for the `strict` mode is `true`.

Notes:

- When a log message matches something expected, that log message is not considered again for the remaining
  expectations. The test fails in that case.
- When a log message is not found for an expectation, then the test fails.

### valid

Use the method `valid` to indicate if the form is valid or not.

```php
public function valid(bool $value): self
```

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

Finally, when all the expectations are in place, the `assert` method needs be used. There are two modalities:

```php
public function assert(bool $strict = true): void
```

- When the `strict` mode is `true`, the number of expected logs must match the number of collected log messages.
- When the `strict` mode is `false`, only the expected logs will be checked against the collected log messages,
  regardless of any unchecked log messages.

The default behaviour for the `strict` mode is `true`.

Notes:

- When a log message matches something expected, that log message is not considered again for the remaining
  expectations. The test fails in that case.
- When a log message is not found for an expectation, then the test fails.

### child

Use the method `child` to perform expectations about form children.

The callable will be called with a new `TestForm` instance for that child, all the `TestForm` methods ara available.

Note: The use of this method will assert about the existence of the child, so if the child does not exist, the test will
fail.

```php
public function child(string $child, callable $expect): self
```

Example: Assert the form has a child called `newChild` which is required and `TextType` type.

```php
$test = new TestForm($form);

$test
    ->child('newChild', static function(TestForm $child) {
        $child
            ->type(TextType::class)
            ->required(true)
        ;
    })
    ->assert()
;
```

### absence

Use the method `absence` to perform expectations about the absence of form children.

```php
public function absence(string $child): self
```

Example: Assert the form has not a child called `newChild`.

```php
$test = new TestForm($form);

$test
    ->absence('newChild')
    ->assert()
;
```

Finally, when all the expectations are in place, the `assert` method needs be used. There are two modalities:

```php
public function assert(bool $strict = true): void
```

- When the `strict` mode is `true`, all the form children should have a expectation. For example, if the form has more
  children than the ones expected, then the test will fail.
- When the `strict` mode is `false`, the test will not fail if some existing child has no an expectation.

The default behaviour for the `strict` mode is `true`.

Note: If you are using `TestForm` within a `TestForm::child` call, then it is not necessary to call `assert`,
the parent `TestForm::assert` will call it.

## TestFormErrors

### expect

Use the method `expect` to indicate the existence of a form error message.

```php
public function expect(Constraint|string $message): self
```

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

### messageTemplate

Use the method `messageTemplate` to indicate the message template for the current form error.

```php
public function messageTemplate(Constraint|string $value): self
```

Example: Assert the error message template.

```php
$test = new TestFormErrors($form->getErrors());

$test
    ->messageTemplate('foo')
    ->assert()
;
```

### parameters

Use the method `parameters` to indicate the paratemers for the current form error.

```php
public function parameters(Constraint|array $value): self
```

Example: Assert the error parameters.

```php
$test = new TestFormErrors($form->getErrors());

$test
    ->parameters(['foo' => 'bar'])
    ->assert()
;
```

### pluralization

Use the method `pluralization` to indicate the pluralization for the current form error.

```php
public function pluralization(Constraint|int $value): self
```

Example: Assert the error pluralization.

```php
$test = new TestFormErrors($form->getErrors());

$test
    ->pluralization(1)
    ->assert()
;
```

### cause

Use the method `cause` to indicate the cause for the current form error.

```php
public function cause(mixed $value): self
```

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

Finally, when all the expectations are in place, the `assert` method needs be used. There are two modalities:

```php
public function assert(bool $strict = true): void
```

- When the `strict` mode is `true`, there must be an expectation for all the form errors, otherwise the test will fail.
- When the `strict` mode is `false`, only the expected form errors will be checked against the form error list,
  regardless of any unchecked form error.

The default behaviour for the `strict` mode is `true`.

Note: If you are using `TestFormErrors` within a `TestForm::errors` call, then it is not necessary to call `assert`,
the `TestForm::assert` will call it.
