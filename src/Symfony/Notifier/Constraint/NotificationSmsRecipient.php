<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Notifier\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Notifier\Recipient\SmsRecipientInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class NotificationSmsRecipient extends Constraint
{
    private readonly Constraint $phone;

    public function __construct(Constraint|string $phone)
    {
        if (!$phone instanceof Constraint) {
            $phone = new IsIdentical($phone);
        }

        $this->phone = $phone;
    }

    public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        if (!$other instanceof SmsRecipientInterface) {
            return false;
        }

        $other = $other->getPhone();
        try {
            $this->phone->evaluate($other, 'recipient phone');
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
        return sprintf('recipient phone %s', $this->phone->toString());
    }
}
