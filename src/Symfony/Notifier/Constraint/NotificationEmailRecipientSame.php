<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Notifier\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class NotificationEmailRecipientSame extends Constraint
{
    public function __construct(
        private readonly string $email,
    ) {
    }

    public function toString(): string
    {
        return sprintf('contains "%s"', $this->email);
    }

    protected function matches(mixed $other): bool
    {
        if (!$other instanceof RecipientInterface) {
            throw new \LogicException('Unable to test a recipient exists for invalid recipients list.');
        }

        if ($other instanceof EmailRecipientInterface) {
            return $other->getEmail() === $this->email;
        }

        return false;
    }

    protected function failureDescription(mixed $other): string
    {
        return 'the Message body ' . $this->toString();
    }
}
