<?php declare(strict_types=1);

namespace Forlond\TestTools;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\ExpectationFailedException;
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

    public function assert(bool $strict = true): void
    {
        $unmatched = [];
        $value     = $this->getOtherValue();
        foreach ($this->getConstraints() as $test) {
            if (!$test->evaluate($value)) {
                $unmatched[] = $test;
            }
        }

        if (!empty($unmatched)) {
            throw new ExpectationFailedException(
                sprintf(
                    'Failed asserting that the following expectations are met: %s',
                    $this->exporter()->export($unmatched)
                )
            );
        }
    }

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

    abstract protected function getOtherValue(): mixed;
}
