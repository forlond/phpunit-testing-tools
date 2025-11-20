<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Symfony\Mailer;

use Forlond\TestTools\Exception\TestFailedException;
use Forlond\TestTools\Symfony\Mailer\Constraint\MessageBodyContains;
use Forlond\TestTools\Symfony\Mailer\TestMailer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final class TestMailerTest extends TestCase
{
    public function testExpectWithStringMatchesMessageBody(): void
    {
        $mailer = new TestMailer();
        $message = (new Email())->text('Welcome aboard, user@example.com!');
        $mailer->send($message);

        $mailer
            ->expect('Welcome aboard')
            ->assert()
        ;
    }

    public function testEnvelopeConstraintReceivesActualEnvelope(): void
    {
        $mailer = new TestMailer();

        $message  = (new Email())->text('Reset token: 123456');
        $envelope = new Envelope(new Address('auth@example.com'), [new Address('user@example.com')]);
        $mailer->send($message, $envelope);

        $mailer
            ->expect(new MessageBodyContains('Reset token'))
            ->envelope(
                self::callback(static function (Envelope $envelope): bool {
                    self::assertSame('auth@example.com', $envelope->getSender()->getAddress());
                    self::assertSame('user@example.com', $envelope->getRecipients()[0]->getAddress());

                    return true;
                })
            )
            ->assert()
        ;
    }

    public function testAssertionFailsWhenExpectedMessageWasNotSent(): void
    {
        $this->expectException(TestFailedException::class);
        $mailer = new TestMailer();
        $mailer
            ->expect('Never delivered')
            ->assert()
        ;
    }
}
