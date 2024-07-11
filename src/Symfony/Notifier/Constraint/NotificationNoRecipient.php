<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Notifier\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Notifier\Recipient\NoRecipient;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

final class NotificationNoRecipient extends Constraint
{
    public function toString(): string
    {
        return 'contains no recipient';
    }

    protected function matches(mixed $other): bool
    {
        if (!$other instanceof RecipientInterface) {
            throw new \LogicException('Unable to test a recipient exists for invalid recipients list.');
        }

        return $other instanceof NoRecipient;
    }

    protected function failureDescription(mixed $other): string
    {
        return 'the Message body ' . $this->toString();
    }
}
