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
        $value     = $this->getValue();

        foreach ($this->getConstraints() as $constraint) {
            if (!$constraint->evaluate($value)) {
                $unmatched[] = $constraint;
            }
        }

        if (!empty($unmatched)) {
            throw new ExpectationFailedException(
                sprintf(
                    'Failed asserting that the following constraints are met: %s',
                    $this->exporter()->export($unmatched)
                )
            );
        }
    }

    abstract protected function getValue(): mixed;

    protected function set(string $name, mixed $expected, callable $actual): void
    {
        if (isset($this->constraints[$name])) {
            throw new \RuntimeException('Cannot redefine ' . $name);
        }

        if (!$expected instanceof Constraint) {
            $expected = new IsIdentical($expected);
        }

        $this->constraints[$name] = new TestConstraint($name, $expected, $actual(...));
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
