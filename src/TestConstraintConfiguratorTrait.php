<?php declare(strict_types=1);

namespace Forlond\TestTools;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;

trait TestConstraintConfiguratorTrait
{
    /**
     * @var array<TestConstraint>
     */
    private array $constraints = [];

    private function set(string $name, mixed $expected, mixed $actual): void
    {
        if (isset($this->constraints[$name])) {
            throw new \RuntimeException('Cannot redefine ' . $name);
        }

        if (!$expected instanceof Constraint) {
            $expected = new IsIdentical($expected);
        }

        $this->constraints[$name] = new TestConstraint($expected, $actual);
    }

    private function evaluate(string $description = ''): void
    {
        foreach ($this->constraints as $field => $test) {
            $test->evaluate(sprintf('%s, %s constraint.', $description, $field));
        }
    }
}
