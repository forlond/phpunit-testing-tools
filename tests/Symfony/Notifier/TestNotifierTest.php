<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Symfony\Notifier;

use Forlond\TestTools\Symfony\Notifier\Constraint\NotificationEmailRecipient;
use Forlond\TestTools\Symfony\Notifier\Constraint\NotificationNoRecipient;
use Forlond\TestTools\Symfony\Notifier\Constraint\NotificationSmsRecipient;
use Forlond\TestTools\Symfony\Notifier\TestNotifier;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\NoRecipient;
use Symfony\Component\Notifier\Recipient\Recipient;

final class TestNotifierTest extends TestCase
{
    public function testItAssertsSubjectContentImportanceEmojiChannelsCustomAndRecipients(): void
    {
        $notification = (new Notification('Incident detected', ['chat/slack', 'sms/twilio']))
            ->content('Disk space is above 95%')
            ->importance(Notification::IMPORTANCE_LOW)
            ->emoji(':warning:');

        $test = new TestNotifier();
        $test->send($notification, new Recipient('ops@example.com', '+123456789'));

        $test
            ->expect('Incident detected')
            ->content(self::stringContains('Disk space'))
            ->importance(self::identicalTo(Notification::IMPORTANCE_LOW))
            ->emoji(':warning:')
            ->channels(['chat/slack', 'sms/twilio'])
            ->custom(static function (Notification $value): bool {
                self::assertSame('Incident detected', $value->getSubject());

                return true;
            })
            ->recipients(
                self::logicalAnd(
                    new NotificationEmailRecipient('ops@example.com'),
                    new NotificationSmsRecipient('+123456789')
                )
            )
            ->assert()
        ;
    }

    public function testItAssertsExceptionDetails(): void
    {
        $notification = Notification::fromThrowable(new \LogicException('Failed to sync', 77));

        $test = new TestNotifier();
        $test->send($notification);

        $test
            ->expect(self::stringStartsWith('LogicException'))
            ->exception(\LogicException::class, self::stringContains('sync'), self::identicalTo(77))
            ->assert()
        ;
    }

    public function testChannelsConstraintCanBeUsed(): void
    {
        $notification = new Notification('Channels', ['sms', 'chat']);

        $test = new TestNotifier();
        $test->send($notification);

        $test
            ->expect('Channels')
            ->channels(self::countOf(2))
            ->assert()
        ;
    }

    public function testRecipientsWithoutArgumentsExpectsNoRecipients(): void
    {
        $notification = new Notification('No recipients');

        $test = new TestNotifier();
        $test->send($notification);

        $test
            ->expect('No recipients')
            ->recipients()
            ->assert()
        ;
    }

    public function testRecipientsConstraintsHandleMultipleTypes(): void
    {
        $notification = new Notification('Mixed recipients');

        $test = new TestNotifier();
        $test->send($notification, new NoRecipient(), new Recipient('alerts@example.com', '+441234567890'));

        $test
            ->expect('Mixed recipients')
            ->recipients(
                new NotificationNoRecipient(),
                self::logicalAnd(
                    new NotificationEmailRecipient('alerts@example.com'),
                    new NotificationSmsRecipient('+441234567890')
                )
            )
            ->assert()
        ;
    }

    public function testAssertionFailsWhenExpectationDoesNotMatch(): void
    {
        $this->expectException(AssertionFailedError::class);

        $test = new TestNotifier();

        $test
            ->expect('Missing notification')
            ->assert()
        ;
    }
}
