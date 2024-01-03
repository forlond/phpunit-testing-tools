<?php declare(strict_types=1);

namespace Forlond\TestTools;

use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractTestCollection extends AbstractTest
{
    private int $current = 0;

    public function assert(bool $strict = true): void
    {
        $unmatched  = [];
        $collection = $this->getCollection();

        foreach ($this->getConstraints() as $constraint) {
            foreach ($collection as $index => $element) {
                if ($constraint->evaluate($element)) {
                    unset($collection[$index]);
                    continue 2;
                }
            }
            $unmatched[] = $constraint;
        }

        if (!empty($unmatched)) {
            throw new ExpectationFailedException(
                sprintf(
                    'Failed asserting that list contains the following expectations: %s',
                    $this->exporter()->export($unmatched)
                )
            );
        }

        if ($strict && !empty($collection)) {
            throw new ExpectationFailedException(
                sprintf(
                    'Failed asserting that list does not contain the following elements: %s',
                    $this->exporter()->export($collection)
                )
            );
        }
    }

    protected function set(string $name, mixed $expected, mixed $actual): void
    {
        $name = sprintf('%d.%s', $this->current, $name);

        parent::set($name, $expected, $actual);
    }

    final protected function next(): void
    {
        $this->current++;
    }

    protected function getConstraints(): array
    {
        $result      = [];
        $constraints = parent::getConstraints();
        foreach ($constraints as $key => $constraint) {
            [$index] = explode('.', $key);
            $list = $result[$index] = $result[$index] ?? new TestConstraintCollection();
            $list->addConstraint($constraint);
        }

        return $result;
    }

    abstract protected function getCollection(): array;

    final protected function getOtherValue(): mixed
    {
        return null;
    }
}
