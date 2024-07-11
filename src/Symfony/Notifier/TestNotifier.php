<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Notifier;

use Forlond\TestTools\AbstractTestGroup;
use Forlond\TestTools\PHPUnit\Constraint\ListArrayContains;
use Forlond\TestTools\Symfony\Notifier\Constraint\NotificationEmailRecipientSame;
use Forlond\TestTools\Symfony\Notifier\Constraint\NotificationNoRecipient;
use Forlond\TestTools\Symfony\Notifier\Constraint\NotificationSmsRecipientSame;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Count;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;
use Symfony\Component\Notifier\Test\Constraint\NotificationSubjectContains;

final class TestNotifier extends AbstractTestGroup implements NotifierInterface
{
    protected const GROUP_NAME = 'notifier';

    private array $notifications = [];

    public function send(Notification $notification, RecipientInterface ...$recipients): void
    {
        $this->notifications[] = ['notification' => $notification, 'recipients' => $recipients];
    }

    public function expect(Constraint|string $notification): self
    {
        if (!$notification instanceof Constraint) {
            $notification = new NotificationSubjectContains($notification);
        }

        $this->next();
        $this->set('notification', $notification, static fn(array $notification) => $notification['notification']);

        return $this;
    }

    public function recipients(Constraint ...$recipients): self
    {
        if (empty($recipients)) {
            $constraint = new Count(0);
        } else {
            $constraint = new ListArrayContains($recipients);
        }

        $this->set('recipients', $constraint, static fn(array $notification) => $notification['recipients']);

        return $this;
    }

    protected function getValue(): array
    {
        return $this->notifications;
    }
}
