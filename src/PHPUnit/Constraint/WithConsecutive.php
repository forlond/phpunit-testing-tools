<?php declare(strict_types=1);

namespace Forlond\TestTools\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class WithConsecutive extends Constraint
{
    private int $invocation = 0;

    private function __construct(
        private array $arguments,
    ) {
    }

    public static function from(array ...$calls): array
    {
        if (count($calls) < 2) {
            throw new \InvalidArgumentException('At least two consecutive arguments must be provided.');
        }

        $count = count(max($calls));
        if (0 === $count) {
            return array_fill(0, count($calls), new self([]));
        }

        foreach ($calls as $i => $arguments) {
            $calls[$i] = array_pad($arguments, $count, self::class);
        }

        return array_map(static fn(array $tuple) => new self($tuple), array_map(null, ...$calls));
    }

    public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        $this->invocation++;
        if ([] === $this->arguments ||self::class === ($argument = array_shift($this->arguments))) {
            throw new ExpectationFailedException(
                sprintf("%s\n%s\nMethod expected parameter but none was provided.", $this->toString(), $description)
            );
        }
        if (!$argument instanceof Constraint) {
            $argument = new IsEqual($argument);
        }

        try {
            return $argument->evaluate($other, $description);
        } catch (ExpectationFailedException $e) {
            if ($returnResult) {
                return false;
            }

            throw new ExpectationFailedException($this->toString() . "\n" . $e->getMessage());
        }
    }

    public function toString(): string
    {
        return 'Invocation #' . $this->invocation . ' failed.';
    }
}
