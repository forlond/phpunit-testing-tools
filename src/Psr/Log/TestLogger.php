<?php declare(strict_types=1);

namespace Forlond\TestTools\Psr\Log;

use Forlond\TestTools\PHPUnit\Constraint\TraversableContainsCallback;
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
    public function assert(bool $strict = true): void
    {
        if ($strict) {
            Assert::assertCount(count($this->expects), $this->logs, __CLASS__);
        }

        $logs = $this->logs;
        foreach ($this->expects as $expect) {
            Assert::assertThat(
                $logs,
                new TraversableContainsCallback(
                    $expect,
                    function(array $expect, array $log, int $index) use (&$logs): bool {
                        ['level' => $logLevel, 'message' => $logMessage, 'context' => $logContext] = $log;
                        ['level' => $level, 'message' => $message, 'context' => $context] = $expect;

                        $result = $logLevel === $level &&
                            $message->evaluate($logMessage, 'message', true) &&
                            (!$context || $context->evaluate($logContext, 'context', true));

                        // When the expected matches, then removes the log from the main list.
                        if ($result) {
                            unset($logs[$index]);
                        }

                        return $result;
                    }
                ),
                __CLASS__
            );
        }
    }
}
