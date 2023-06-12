<?php declare(strict_types=1);

namespace Forlond\TestTools\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\TraversableContains;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TraversableContainsCallback extends TraversableContains
{
    public function __construct(
        mixed                     $value,
        private readonly \Closure $callback,
    ) {
        parent::__construct($value);
    }

    /**
     * @inheritDoc
     */
    protected function matches(mixed $other): bool
    {
        foreach ($other as $index => $element) {
            $result = ($this->callback)($this->value(), $element, $index);
            if (!is_bool($result)) {
                throw new \RuntimeException('Closure must return a bool value.');
            }
            if ($result) {
                return true;
            }
        }

        return false;
    }
}
