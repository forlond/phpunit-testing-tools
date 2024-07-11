<?php declare(strict_types=1);

namespace Forlond\TestTools\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;

final class ListArrayContains extends Constraint
{
    public function __construct(
        private readonly array $value,
    ) {
        if (!array_is_list($this->value)) {
            throw new \InvalidArgumentException(
                'Cannot use this constraint with non list array, use AssociativeArrayContains instance instead.'
            );
        }
    }

    public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        $success = true;
        $errors  = [];

        foreach ($this->value as $index => $value) {
            if (!$value instanceof Constraint) {
                $value = new IsIdentical($value);
            }

            if (!(new ArrayHasKey($index))->evaluate($other, 'key', true) ||
                !$value->evaluate($other[$index], 'value', true)) {
                $success = false;
                $errors[] = sprintf('%d. %s', $index, $value->failureDescription($other[$index]));
            }
        }

        if ($returnResult) {
            return $success;
        }

        if (!$success) {
            $this->fail($errors, $description);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return 'contains another list array';
    }

    protected function failureDescription(mixed $other): string
    {
        return implode("\n", $other);
    }
}
