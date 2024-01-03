<?php declare(strict_types=1);

namespace Forlond\TestTools;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;
use SebastianBergmann\Exporter\Exporter;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractTest implements TestInterface
{
    /**
     * @var array<TestConstraintInterface>
     */
    private array $constraints = [];

    private ?Exporter $exporter = null;

    protected function set(string $name, mixed $expected, mixed $actual): void
    {
        if (isset($this->constraints[$name])) {
            throw new \RuntimeException('Cannot redefine ' . $name);
        }

        if (!$expected instanceof Constraint) {
            $expected = new IsIdentical($expected);
        }

        $this->constraints[$name] = new TestConstraint($name, $expected, $actual);
    }

    /**
     * @return array<TestConstraintInterface>
     */
    protected function getConstraints(): array
    {
        return $this->constraints;
    }

    protected function exporter(): Exporter
    {
        if ($this->exporter === null) {
            $this->exporter = new Exporter();
        }

        return $this->exporter;
    }
}
