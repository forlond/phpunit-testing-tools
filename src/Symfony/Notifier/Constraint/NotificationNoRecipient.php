<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Notifier\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Notifier\Recipient\NoRecipient;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class NotificationNoRecipient extends Constraint
{
    public function toString(): string
    {
        return 'contains no recipient instance';
    }

    protected function matches(mixed $other): bool
    {
        return $other instanceof NoRecipient;
    }

    protected function failureDescription(mixed $other): string
    {
        return 'the notification ' . $this->toString();
    }
}
