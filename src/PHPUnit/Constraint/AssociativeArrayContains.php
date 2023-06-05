<?php declare(strict_types=1);

namespace Forlond\TestTools\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class AssociativeArrayContains extends Constraint
{
    public function __construct(
        private readonly array $value,
        private readonly bool  $strict = true,
    ) {
        if (array_is_list($this->value)) {
            throw new \InvalidArgumentException(
                'Cannot use this constraint with non associative array, use TraversableContains instance instead.'
            );
        }
    }

    /**
     * @inheritDoc
     */
    protected function matches(mixed $other): bool
    {
        foreach ($this->value as $key => $value) {
            if (!$value instanceof Constraint) {
                $value = new IsIdentical($value);
            }

            if (!(new ArrayHasKey($key))->evaluate($other, 'key', true) ||
                !$value->evaluate($other[$key], 'value', true)) {
                return false;
            }

            unset($other[$key]);
        }

        return !$this->strict || empty($other);
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return 'contains another associative array';
    }
}
