# PhpUnit

## Constraints

### ArrayContains

The `ArrayContains` constraint allows you to assert whether an array contains at least one element matching a given
value or condition.

It is especially useful when you want to verify the presence of a specific element without requiring an exact match of
all array contents.

```php
// The array matches completely.
new ArrayContains(['one' => 1, 'two' => 2])

// The array matches partially.
new ArrayContains(['one' => 1], false)

// Any constraint can be used to assert specific elements.
new ArrayContains(['one' => self::lessThan(2), 'two' => 2])

// Multi-dimensional array.
new ArrayContains([
    'one' => new ArrayContains(['es' => 'uno'], false),
])
```

### WithConsecutive

This constraint should be used to replace the `withConsecutive` method starting with PhpUnit 10 or higher.

```php
// Before
$this
    ->createMock(CalculatorInterface::class)
    ->expects($this->exactly(2))
    ->method('sum')
    ->withConsecutive([1, 2], [4, 5])
    ->willReturnOnConsecutiveCalls(3, 9)
;

// After
$this
    ->createMock(CalculatorInterface::class)
    ->expects($this->exactly(2))
    ->method('sum')
    ->with(...WithConsecutive::from([1, 2], [4, 5]))
    ->willReturnOnConsecutiveCalls(3, 9)
;
```

> [!NOTE]
> `WithConsecutive` will throw an exception if the number of argument groups is less than two. In that case, use `with`
> instead.

- You can also explore additional approaches in
  this [StackOverflow discussion](https://stackoverflow.com/questions/75389000/replace-phpunit-method-withconsecutive-abandoned-in-phpunit-10)
