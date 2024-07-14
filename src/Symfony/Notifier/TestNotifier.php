<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Notifier;

use Forlond\TestTools\AbstractTestGroup;
use Forlond\TestTools\PHPUnit\Constraint\ArrayContains;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Count;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\NoRecipient;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestNotifier extends AbstractTestGroup implements NotifierInterface
{
    protected const GROUP_NAME = 'notifier';

    private array $notifications = [];

    public function send(Notification $notification, RecipientInterface ...$recipients): void
    {
        $this->notifications[] = ['notification' => $notification, 'recipients' => $recipients];
    }

    public function expect(Constraint|string $subject): self
    {
        $this->next();
        $this->set(
            'notification_subject',
            $subject,
            /** @param array{'notification': Notification} $notification */
            static fn(array $notification) => $notification['notification']->getSubject()
        );

        return $this;
    }

    public function content(Constraint|string $content): self
    {
        $this->set(
            'notification_content',
            $content,
            /** @param array{'notification': Notification} $notification */
            static fn(array $notification) => $notification['notification']->getContent()
        );

        return $this;
    }

    public function importance(Constraint|string $importance): self
    {
        $this->set(
            'notification_importance',
            $importance,
            /** @param array{'notification': Notification} $notification */
            static fn(array $notification) => $notification['notification']->getImportance()
        );

        return $this;
    }

    public function emoji(Constraint|string $emoji): self
    {
        $this->set(
            'notification_emoji',
            $emoji,
            /** @param array{'notification': Notification} $notification */
            static fn(array $notification) => $notification['notification']->getEmoji()
        );

        return $this;
    }

    public function exception(
        string                 $class,
        Constraint|string|null $mesage = null,
        Constraint|int|null    $code = null,
    ): self {
        $this->set(
            'notification_exception_class',
            $class,
            /** @param array{'notification': Notification} $notification */
            static fn(array $notification) => $notification['notification']->getException()?->getClass()
        );
        if ($mesage) {
            $this->set(
                'notification_exception_message',
                $mesage,
                /** @param array{'notification': Notification} $notification */
                static fn(array $notification) => $notification['notification']->getException()?->getMessage()
            );
        }
        if ($code) {
            $this->set(
                'notification_exception_code',
                $code,
                /** @param array{'notification': Notification} $notification */
                static fn(array $notification) => $notification['notification']->getException()?->getCode()
            );
        }

        return $this;
    }

    public function channels(Constraint|array $channels): self
    {
        $this->set(
            'notification_channels',
            $channels,
            /** @param array{'notification': Notification} $notification */
            static fn(array $notification) => $notification['notification']->getChannels(new NoRecipient())
        );

        return $this;
    }

    public function custom(callable $callback): self
    {
        $constraint = new Callback($callback);

        $this->set('notification_custom', $constraint, static fn(array $notification) => $notification['notification']);

        return $this;
    }

    public function recipients(Constraint ...$recipients): self
    {
        if (empty($recipients)) {
            $constraint = new Count(0);
        } else {
            $constraint = new ArrayContains($recipients);
        }

        $this->set('recipients', $constraint, static fn(array $notification) => $notification['recipients']);

        return $this;
    }

    protected function getValue(): array
    {
        return $this->notifications;
    }
}
