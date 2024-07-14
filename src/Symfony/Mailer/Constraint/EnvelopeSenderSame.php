<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Mailer\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Mailer\Envelope;

final class EnvelopeSenderSame extends Constraint
{
    public function __construct(
        private readonly string $address,
    ) {
    }

    public function toString(): string
    {
        return sprintf('contains sender "%s"', $this->address);
    }

    protected function matches(mixed $other): bool
    {
        if (null === $other) {
            return false;
        }

        if (!$other instanceof Envelope) {
            throw new \LogicException('Unable to test a envelope sender on non Envelope instance.');
        }

        return $other->getSender()->toString() === $this->address;
    }

    protected function failureDescription(mixed $other): string
    {
        return 'the Envelope ' . $this->toString();
    }
}
