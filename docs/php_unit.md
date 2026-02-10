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
