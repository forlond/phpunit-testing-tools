<?php declare(strict_types=1);

namespace Forlond\TestTools;

use Forlond\TestTools\Exception\TestFailedException;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestConstraintGroup implements TestConstraintInterface
{
    public function __construct(
        /**
         * @var array<TestConstraintInterface>
         */
        private readonly array $constraints,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function evaluate(mixed $other, bool $returnResult = false): ?bool
    {
        $failed = [];
        foreach ($this->constraints as $constraint) {
            try {
                $constraint->evaluate($other);
            } catch (ExpectationFailedException $e) {
                $failed[] = $e;
            }
        }

        if ($returnResult) {
            return empty($failed);
        }

        if (!empty($failed)) {
            throw new ExpectationFailedException((new TestFailedException($failed))->getMessage());
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        $values = array_map(
            static fn(TestConstraintInterface $constraint) => $constraint->toString(),
            $this->constraints
        );

        return implode("\n", $values);
    }
}
