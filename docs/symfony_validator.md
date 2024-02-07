# Symfony/Validator

## Integration

Use one of the following abstract test cases:

- `AbstractValidatorTestCase` for general validation purposes.
- `TestConstraintViolationList` to perform expectations to any `ConstraintViolationListInterface` instance.

## AbstractValidatorTestCase

Provides a base for any test that uses the validator component.

### Methods

```php
final protected function validate(
    mixed                      $value,
    Constraint|array           $constraints = null,
    GroupSequence|array|string $groups = null,
    ?callable                  $configure = null,
): TestConstraintViolationList;
```

It calls the `validate` validator method.

The validator instance can be configured when passing a `$configure` callable.

```php
final class MyTestValidation extends AbstractValidatorTestCase
{
    public function testExample1(): void
    {
        $violations = $this->validate('value');

        // ...
    }

    public function testExample2(): void
    {
        $violations = $this->validate(
            'value',
            null,
            null,
            static function(ValidatorBuilder $builder): void {
                $builder->setTranslationDomain('admin');
            }
        );

        // ...
    }
}
```

---

```php
final protected function validateProperty(
    object                     $object,
    string                     $propertyName,
    GroupSequence|array|string $groups = null,
    ?callable                  $configure = null,
): TestConstraintViolationList
```

It calls the `validateProperty` validator method.

The validator instance can be configured when passing a `$configure` callable.

```php
final class MyTestValidation extends AbstractValidatorTestCase
{
    public function testExample1(): void
    {
        $object     = new MyObject();
        $violations = $this->validateProperty($object, 'property');

        // ...
    }

    public function testExample2(): void
    {
        $object     = new MyObject();
        $violations = $this->validateProperty(
            $object,
            'property',
            null,
            static function(ValidatorBuilder $builder): void {
                $builder->setTranslationDomain('admin');
            }
        );

        // ...
    }
}
```

---

```php
final protected function validatePropertyValue(
    object|string              $objectOrClass,
    string                     $propertyName,
    mixed                      $value,
    GroupSequence|array|string $groups = null,
    ?callable                  $configure = null,
): TestConstraintViolationList
```

It calls the `validatePropertyValue` validator method.

The validator instance can be configured when passing a `$configure` callable.

```php
final class MyTestValidation extends AbstractValidatorTestCase
{
    public function testExample1(): void
    {
        $object     = new MyObject();
        $violations = $this->validatePropertyValue($object, 'property', 'value');

        // ...
    }

    public function testExample2(): void
    {
        $object     = new MyObject();
        $violations = $this->validatePropertyValue(
            $object,
            'property',
            'value',
            null,
            static function(ValidatorBuilder $builder): void {
                $builder->setTranslationDomain('admin');
            }
        );

        // ...
    }
}
```

---

```php
final protected function createExecutionContext(
    mixed     $root,
    ?callable $configure = null,
): ExecutionContextInterface
```

Allows to create a new execution context. This is useful for `Callback` constraints.

```php
class MyObject
{
    #[
        Assert\Callback()
    ]
    public function validateClass(ExecutionContextInterface $context): void
    {
        // ... validation logic ...
    }
}

final class MyTestValidation extends AbstractValidatorTestCase
{
    public function testExample1(): void
    {
        $object  = new MyObject();
        $context = $this->createExecutionContext($object, null);

        $object->validateClass($context);

        $violations = new TestConstraintViolationList($context->getViolations());

        // ...
    }

    public function testExample2(): void
    {
        $object  = new MyObject();
        $context = $this->createExecutionContext(
            $object,
            static function(TestExecutionContextFactory $factory, ValidatorBuilder $builder) {
                $factory->translationDomain = 'admin';
                $builder->setTranslationDomain('admin');
            }
        );

        $object->validateClass($context);

        $violations = new TestConstraintViolationList($context->getViolations());

        // ...
    }
}
```

---

```php
protected function configureBuilder(): ValidatorBuilder;
```

Allows to configure the `ValidatorInterface` instance with default settings for all the test cases in the same test
class.

```php
final class MyTestValidation extends AbstractValidatorTestCase
{
    public function testExample1(): void
    {
        $violations = $this->validate('value');

        // ...
    }

    protected function configureBuilder()
    {
        $builder = parent::configureBuilder();
        $builder->enableAnnotationMapping();

        return $builder;
    }
}
```

However, it is possible to change the default settings for a specific test case if necessary.

```php
final class MyTestValidation extends AbstractValidatorTestCase
{
    public function testExample1(): void
    {
        $violations = $this->validate(
            'value',
            null,
            null,
            static function(ValidatorBuilder $builder): void {
                $builder->disableAnnotationMapping();
            }
        );

        // ...
    }

    protected function configureBuilder()
    {
        $builder = parent::configureBuilder();
        $builder->enableAnnotationMapping();

        return $builder;
    }
}
```

## TestConstraintViolationList

The `validate`, `validateProperty` and `validatePropertyValue` methods return a `TestConstraintViolationList` instance.

The `TestConstraintViolationList` class allows to define expectations against the `ConstraintViolationListInterface`
returned by the validator.

```php
public function expect(Constraint|string $message): self
```

Use the `expect` method to start an expectation definition. The `expect` order invocation is relevant,
but it can be disabled by using the method `disableStrictSequence`.

Also, it is possible to continue defining the expectation with the following methods.

It is possible to continue defining the expectation with the following methods.

---

```php
public function path(Constraint|string $value): self
```

Assert the violation property path.

---

```php
public function parameters(Constraint|array $value): self
```

Assert the violation parameters.

---

```php
public function messageTemplate(Constraint|string $value): self
```

Assert the violation message template.

---

```php
public function invalidValue(mixed $value): self
```

Assert the violation invalid value.

---

```php
public function plural(Constraint|int $value): self
```

Assert the violation plural value.

---

```php
public function constraint(Constraint|string $value): self
```

Assert the violation constraint value.

When passing a string value, the value will be transformed in an IsInstanceOf constraint.

---

```php
public function code(Constraint|string $value): self
```

Assert the violation code value.

---

```php
public function root(mixed $value): self
```

Assert the violation root value.


> [!TIP]
> PHPUnit `Constraint` instances can also be used which provides more powerful assertions.

Once a violation expectation is defined, it is possible to define another one by using again the `expect` method.

When there are no more expectations to define, use the `assert` method.

```php
final class MyTestValidation extends AbstractValidatorTestCase
{
    public function testExample1(): void
    {
        $object     = new MyObject();
        $violations = $this->validate($object);

        // Assert the violation list has only a violation with "foo.bar.message" as message.
        $violations
            ->expect('foo.bar.message')
            ->assert()
        ;
    }

    public function testExample2(): void
    {
        $object     = new MyObject();
        $violations = $this->validate($object);

        // Assert the violation list has only a violation with "foo.bar.message" as message and the constraint violation
        // properties must have the same indicated values.
        $violations
            ->expect('foo.bar.message')
            ->path('property')
            ->parameters([
                '%value%' => 'test',
            ])
            ->messageTemplate('foo.bar.message')
            ->invalidValue(null)
            ->plural(0)
            ->code('code')
            ->constraint(NotNull::class)
            ->root($object)
            ->assert()
        ;
    }

    public function testExample3(): void
    {
        $object     = new MyObject();
        $violations = $this->validate($object);

        // Assert the violation list has exactly two violations.
        $violations
            ->expect('foo.bar.message')
            ->expect('foo.baz.message')
            ->assert()
        ;
    }

    public function testExample4(): void
    {
        $object     = new MyObject();
        $violations = $this->validate($object);

        // Assert the violation list has one violation although the list may have more violations.
        $violations
            ->expect('foo.bar.message')
            ->disableStrictSize()
            ->assert()
        ;
    }
}
```

### Configure custom constraint validators

> [!IMPORTANT]
> Use this option when it is necessary to register constraint validators with dependencies.
> Constraint validators without dependecies (i.e.: NotNull, Expression) do not need to be registered explicitly.

If the validation logic requires custom `ConstraintValidatorInterface` instances, it is possible to configure them.
The `TestConstraintValidatorFactory` allows to set any `ConstraintValidatorInterface` instance.

Let's imagine `CustomValidator` is the custom constraint validator, so a property class is configured with a `Custom`
constraint.

```php
use Symfony\Component\Validator\ConstraintValidator;

class CustomValidator extends ConstraintValidator
{
    public function __construct(
        private readonly MyService $service,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if ($value !== $this->service->getSetting()) {
            $this
                ->context
                ->buildViolation('foo.bar.message')
                ->addViolation()
            ;
        }
    }
}
```

When the object is validated, the validator will try to execute the `CustomValidator`. However, the `CustomValidator`
class cannot be instantiaded automatically because it requires dependencies.

In order to execute the validator without runtime errors, it is necessary to register the `CustomValidator` in the
`TestConstraintValidatorFactory`.

Depending on the need, the constraint validator can be configured in different ways.

```php
final class MyTestValidation extends AbstractValidatorTestCase
{
    public function testExample1(): void
    {
        $object     = new MyObject();
        $violations = $this->validate(
            $object,
            null,
            null,
            static function(ValidatorBuilder $builder): void {
                $factory = new TestConstraintValidatorFactory();
                $builder = parent::configureBuilder();
                $builder->setConstraintValidatorFactory($factory);

                // Example, ignore the constraint validator.
                // This should be the recommended option as you may have a test case apart for the constraint validator.
                $factory->setNoopInstance(CustomValidator::class);
            }
        );

        // ...
    }

    public function testExample2(): void
    {
        $object     = new MyObject();
        $violations = $this->validate(
            $object,
            null,
            null,
            function(ValidatorBuilder $builder): void {
                $factory = new TestConstraintValidatorFactory();
                $builder = parent::configureBuilder();
                $builder->setConstraintValidatorFactory($factory);

                // Example, the constraint validator is mocked.
                // This can be useful if it is necessary to assert that the constraint validator is called.
                $mock = $this->createMock(CustomValidator::class);
                $factory->setInstance(CustomValidator::class, $mock);

                $mock
                    ->method('validate')
                    ->expect(self:never())
                ;
            }
        );

        // ...
    }

    public function testExample3(): void
    {
        $object     = new MyObject();
        $violations = $this->validate(
            $object,
            null,
            null,
            function(ValidatorBuilder $builder): void {
                $factory = new TestConstraintValidatorFactory();
                $builder = parent::configureBuilder();
                $builder->setConstraintValidatorFactory($factory);

                // Example, the constraint validator dependency is mocked.
                // This can be useful if it is necessary to test the constraint validator logic.
                $mock = $this->createMock(MyService::class);
                $factory->setInstance(CustomValidator::class, new CustomValidator($mock));

                $mock
                    ->method('getSetting')
                    ->expect(self:once())
                    ->willReturn(1)
                ;
            }
        );

        // ...
    }
}
```
