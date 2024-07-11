<?php declare(strict_types=1);

namespace Forlond\TestTools;

use Forlond\TestTools\Exception\TestFailedException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Exporter\Exporter;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractTestGroup extends AbstractTest
{
    protected const GROUP_NAME = 'group';

    private int $current = 0;

    private bool $strictSequence = true;

    private bool $strictSize = true;

    private ?Exporter $exporter = null;

    final public function disableStrictSequence(): self
    {
        $this->strictSequence = false;

        return $this;
    }

    final public function disableStrictSize(): self
    {
        $this->strictSize = false;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function assert(): void
    {
        $failed = [];
        $group  = $this->getValue();

        foreach ($this->getConstraints() as $i => $constraint) {
            assert($constraint instanceof TestConstraintGroup);
            if ($this->strictSequence) {
                $element = array_shift($group);
                if (null === $element) {
                    $failed[] = new ExpectationFailedException(
                        sprintf(
                            "Failed asserting that the %s contains an element at index %d.",
                            static::GROUP_NAME,
                            $i,
                        )
                    );
                } else {
                    try {
                        $constraint->evaluate($element);
                    } catch (ExpectationFailedException $e) {
                        $failed[] = new ExpectationFailedException(
                            sprintf(
                                "Failed asserting that the %s contains an element at index %d that matches the following constraint(s):\n%s",
                                static::GROUP_NAME,
                                $i,
                                $e->getMessage(),
                            )
                        );
                    }
                }
            } else {
                foreach ($group as $index => $element) {
                    if ($constraint->evaluate($element, true)) {
                        unset($group[$index]);
                        continue 2;
                    }
                }
                $failed[] = new ExpectationFailedException(
                    sprintf(
                        "Failed asserting that the %s contains an element that matches the following constraint:\n%s",
                        static::GROUP_NAME,
                        $constraint->toString()
                    )
                );
            }
        }

        if ($this->strictSize && !empty($group)) {
            $failed[] = new ExpectationFailedException(
                sprintf(
                    "Failed asserting that the %s does not contain the following elements:\n%s",
                    static::GROUP_NAME,
                    $this->getExporter()->export($group)
                )
            );
        }

        if (!empty($failed)) {
            throw new TestFailedException($failed, $this->failureDescription());
        }

        Assert::assertEmpty($failed);
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

    private function getExporter(): Exporter
    {
        if (null === $this->exporter) {
            $this->exporter = new Exporter();
        }

        return $this->exporter;
    }
}
