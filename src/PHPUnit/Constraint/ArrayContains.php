<?php declare(strict_types=1);

namespace Forlond\TestTools\PHPUnit\Constraint;

use Forlond\TestTools\Exception\TestFailedException;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class ArrayContains extends Constraint
{
    public function __construct(
        private readonly array $value,
        private readonly bool  $strict = true,
    ) {
    }

    public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        if (!is_array($other)) {
            throw new \LogicException('Unable to test non array value.');
        }

        $errors = [];
        foreach ($this->value as $key => $value) {
            if (!array_key_exists($key, $other)) {
                $errors[] = new ExpectationFailedException(
                    sprintf('Failed asserting that key/index "%s" exist.', $key)
                );
                continue;
            }

            if (!$value instanceof Constraint) {
                $value = new IsIdentical($value);
            }
            try {
                $value->evaluate($other[$key], sprintf('- key/index: %s', $key));
            } catch (ExpectationFailedException $e) {
                $errors[] = $e;
            }
            unset($other[$key]);
        }

        if ($this->strict && !empty($other)) {
            foreach ($other as $key => $value) {
                $errors[] = new ExpectationFailedException(
                    sprintf('Failed asserting that key/index "%s" does not exist.', $key)
                );
            }
        }

        if ($returnResult) {
            return empty($errors);
        }

        if (!empty($errors)) {
            throw new ExpectationFailedException((new TestFailedException($errors, $description))->getMessage());
        }

        return null;
    }

    public function toString(): string
    {
        return 'contains another array';
    }
}
