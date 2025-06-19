<?php declare(strict_types=1);

namespace Forlond\TestTools\Psr\Log;

use Forlond\TestTools\AbstractTestGroup;
use Forlond\TestTools\TestResettable;
use PHPUnit\Framework\Constraint\Constraint;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestLogger extends AbstractTestGroup implements TestResettable, LoggerInterface
{
    use LoggerTrait;

    protected const GROUP_NAME = 'logger';

    private array $logs = [];

    /**
     * @param string|\Stringable $message
     *
     * @inheritDoc
     */
    public function log($level, $message, array $context = []): void
    {
        // Backward compatibility for $message as a non type-hinted argument in v1.x
        if (!is_string($message) && !$message instanceof \Stringable) {
            throw new \InvalidArgumentException('Invalid message value. Use string or Stringable instance.');
        }

        $this->logs[] = ['level' => $level, 'message' => (string) $message, 'context' => $context];
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    public function expect(string $level, Constraint|\Stringable|string $message, Constraint|array|null $context): self
    {
        $this->next();
        $this->set('level', $level, static fn(array $log) => $log['level']);
        $this->set('message', $message, static fn(array $log) => $log['message']);
        if (null !== $context) {
            $this->set('context', $context, static fn(array $log) => $log['context']);
        }

        return $this;
    }

    public function reset(): void
    {
        $this->logs = [];
    }

    protected function getValue(): array
    {
        return $this->logs;
    }
}
