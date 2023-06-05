<?php declare(strict_types=1);

namespace Forlond\TestTools\Psr\Log;

use Forlond\TestTools\PHPUnit\Constraint\TraversableContainsCallback;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;
use Psr\Log\AbstractLogger;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestLogger extends AbstractLogger
{
    private array $logs = [];

    private array $expects = [];

    /**
     * @inheritDoc
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->logs[] = ['level' => $level, 'message' => $message, 'context' => $context];
    }

    #[ArrayShape([
        'level'   => 'string',
        'message' => 'string',
        'context' => 'array',
    ])]
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function expect(string $level, Constraint|\Stringable|string $message, Constraint|array|null $context): self
    {
        if (!$message instanceof Constraint) {
            $message = new IsIdentical($message);
        }
        if ($context && !$context instanceof Constraint) {
            $context = new IsIdentical($context);
        }

        $this->expects[] = ['level' => $level, 'message' => $message, 'context' => $context];

        return $this;
    }

    /**
     * Compare the indicated expects against the registered log messages.
     * Use the strict option to assert the amount of log messages.
     */
    public function assert(bool $strict = false): void
    {
        if ($strict) {
            Assert::assertCount(count($this->expects), $this->logs);
        }

        foreach ($this->expects as $expect) {
            Assert::assertThat(
                $this->logs,
                new TraversableContainsCallback(
                    $expect,
                    function(array $expect, array $log): bool {
                        ['level' => $logLevel, 'message' => $logMessage, 'context' => $logContext] = $log;
                        ['level' => $level, 'message' => $message, 'context' => $context] = $expect;

                        return $logLevel === $level &&
                            $message->evaluate($logMessage, 'message', true) &&
                            (!$context || $context->evaluate($logContext, 'context', true));
                    }
                )
            );
        }
    }
}
