<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Mailer\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mime\Test\Constraint\EmailTextBodyContains;

final class MessageBodyContains extends Constraint
{
    private string $expectedText;

    public function __construct(string $expectedText)
    {
        $this->expectedText = $expectedText;
    }

    public function toString(): string
    {
        return sprintf('contains "%s"', $this->expectedText);
    }

    protected function matches(mixed $other): bool
    {
        if ($other instanceof Email) {
            $constraint = new EmailTextBodyContains($this->expectedText);

            return $constraint->matches($other);
        }

        if ($other instanceof Message) {
            $other = $other->getBody()?->toString() ?? '';
        } elseif (RawMessage::class === $other::class) {
            $other = $other->toString();
        }

        return str_contains($other, $this->expectedText);
    }

    protected function failureDescription(mixed $other): string
    {
        return 'the Message body ' . $this->toString();
    }
}
