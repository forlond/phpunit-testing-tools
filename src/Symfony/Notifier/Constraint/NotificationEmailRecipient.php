<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Notifier\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
class NotificationEmailRecipient extends Constraint
{
    private readonly Constraint $email;

    public function __construct(Constraint|string $email)
    {
        if (!$email instanceof Constraint) {
            $email = new IsIdentical($email);
        }

        $this->email = $email;
    }

    public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        if (!$other instanceof EmailRecipientInterface) {
            return false;
        }

        $other = $other->getEmail();
        try {
            $this->email->evaluate($other, 'recipient email');
        } catch (ExpectationFailedException $e) {
            if ($returnResult) {
                return false;
            }

            throw $e;
        }

        if ($returnResult) {
            return true;
        }

        return null;
    }

    public function toString(): string
    {
        return sprintf('recipient email %s', $this->email->toString());
    }
}
