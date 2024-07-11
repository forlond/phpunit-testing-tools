<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Notifier\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Notifier\Recipient\RecipientInterface;
use Symfony\Component\Notifier\Recipient\SmsRecipientInterface;

final class NotificationSmsRecipientSame extends Constraint
{
    public function __construct(
        private readonly string $phone,
    ) {
    }

    public function toString(): string
    {
        return sprintf('contains "%s"', $this->phone);
    }

    protected function matches(mixed $other): bool
    {
        if (!$other instanceof RecipientInterface) {
            throw new \LogicException('Unable to test a recipient exists for invalid recipients list.');
        }

        if ($other instanceof SmsRecipientInterface) {
            return $other->getPhone() === $this->phone;
        }

        return false;
    }

    protected function failureDescription(mixed $other): string
    {
        return 'the Message body ' . $this->toString();
    }
}
