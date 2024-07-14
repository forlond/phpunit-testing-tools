<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Mailer;

use Forlond\TestTools\AbstractTestGroup;
use Forlond\TestTools\Symfony\Mailer\Constraint\MessageBodyContains;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

final class TestMailer extends AbstractTestGroup implements MailerInterface
{
    protected const GROUP_NAME = 'mailer';

    private array $data = [];

    public function send(RawMessage $message, ?Envelope $envelope = null): void
    {
        $this->data[] = ['message' => $message, 'envelope' => $envelope];
    }

    public function expect(Constraint|string $message): self
    {
        if (!$message instanceof Constraint) {
            $message = new MessageBodyContains($message);
        }

        $this->next();
        $this->set('message', $message, static fn(array $data) => $data['message']);

        return $this;
    }

    public function envelope(Constraint $envelope): self
    {
        $this->set('envelope', $envelope, static fn(array $data) => $data['envelope']);

        return $this;
    }

    protected function getValue(): array
    {
        return $this->data;
    }
}
