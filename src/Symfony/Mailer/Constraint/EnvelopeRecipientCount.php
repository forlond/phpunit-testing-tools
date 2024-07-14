<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Mailer\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Mailer\Envelope;

final class EnvelopeRecipientCount extends Constraint
{
    public function __construct(
        private readonly int $expectedValue,
    ) {
    }

    public function toString(): string
    {
        return sprintf('has "%d" recipients', $this->expectedValue);
    }

    protected function matches(mixed $other): bool
    {
        if (null === $other) {
            return false;
        }

        if (!$other instanceof Envelope) {
            throw new \LogicException('Unable to test a envelope recipients count on non Envelope instance.');
        }

        return count($other->getRecipients()) === $this->expectedValue;
    }

    protected function failureDescription(mixed $other): string
    {
        return 'the Envelope ' . $this->toString();
    }
}
