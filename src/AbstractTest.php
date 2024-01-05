<?php declare(strict_types=1);

namespace Forlond\TestTools;

use Forlond\TestTools\Exception\TestFailedException;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractTest implements TestInterface
{
    /**
     * @var array<TestConstraintInterface>
     */
    private array $constraints = [];

    /**
     * @inheritDoc
     */
    public function assert(bool $strict = true): void
    {
        $failed = [];
        $value  = $this->getValue();

        foreach ($this->getConstraints() as $constraint) {
            try {
                $constraint->evaluate($value);
            } catch (ExpectationFailedException $e) {
                $failed[] = $e;
            }
        }

        if (!empty($failed)) {
            throw new TestFailedException($failed, $this->failureDescription());
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

    protected function failureDescription(): ?string
    {
        return null;
    }
}
