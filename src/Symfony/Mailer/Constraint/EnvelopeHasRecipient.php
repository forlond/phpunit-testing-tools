<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Mailer\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Mailer\Envelope;

final class EnvelopeHasRecipient extends Constraint
{
    public function __construct(
        private readonly string $address,
    ) {
    }

    public function toString(): string
    {
        return sprintf('contains recipient "%s"', $this->address);
    }

    protected function matches(mixed $other): bool
    {
        if (null === $other) {
            return false;
        }

        if (!$other instanceof Envelope) {
            throw new \LogicException('Unable to test a envelope recipient on non Envelope instance.');
        }

        foreach ($other->getRecipients() as $recipient) {
            if ($recipient->toString() === $this->address) {
                return true;
            }
        }

        return false;
    }

    protected function failureDescription(mixed $other): string
    {
        return 'the Envelope ' . $this->toString();
    }
}
