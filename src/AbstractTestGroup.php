<?php declare(strict_types=1);

namespace Forlond\TestTools;

use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractTestGroup extends AbstractTest
{
    private int $current = 0;

    public function assert(bool $strict = true): void
    {
        $unmatched = [];
        $group     = $this->getValue();

        foreach ($this->getConstraints() as $constraint) {
            foreach ($group as $index => $element) {
                if ($constraint->evaluate($element)) {
                    unset($group[$index]);
                    continue 2;
                }
            }
            $unmatched[] = $constraint;
        }

        if (!empty($unmatched)) {
            throw new ExpectationFailedException(
                sprintf(
                    'Failed asserting that list contains the following constraint groups: %s',
                    $this->exporter()->export($unmatched)
                )
            );
        }

        if ($strict && !empty($group)) {
            throw new ExpectationFailedException(
                sprintf(
                    'Failed asserting that list does not contain the following elements: %s',
                    $this->exporter()->export($group)
                )
            );
        }
    }

    abstract protected function getValue(): array;

    protected function set(string $name, mixed $expected, callable $actual): void
    {
        parent::set(sprintf('%d.%s', $this->current, $name), $expected, $actual);
    }

    final protected function next(): void
    {
        $this->current++;
    }

    protected function getConstraints(): array
    {
        $groups      = [];
        $constraints = parent::getConstraints();

        foreach ($constraints as $key => $constraint) {
            [$index] = explode('.', $key);
            $groups[$index][] = $constraint;
        }

        return array_map(static fn(array $constraints) => new TestConstraintGroup($constraints), $groups);
    }
}
