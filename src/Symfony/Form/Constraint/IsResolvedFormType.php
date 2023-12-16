<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Form\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use Symfony\Component\Form\ResolvedFormType;

final class IsResolvedFormType extends Constraint
{
    private readonly IsInstanceOf $delegate;

    public function __construct(
        private readonly string $type,
    ) {
        $this->delegate = new IsInstanceOf($this->type);
    }

    public function evaluate($other, string $description = '', bool $returnResult = false): ?bool
    {
        if ($other instanceof ResolvedFormType) {
            $other = $other->getInnerType();
        }

        return $this->delegate->evaluate($other, $description, $returnResult);
    }

    /**
     * @inheritDoc
     */
    public function matches($other): bool
    {
        if ($other instanceof ResolvedFormType) {
            $other = $other->getInnerType();
        }

        return $this->delegate->matches($other);
    }

    public function toString(): string
    {
        return sprintf('form type is an instance of %s', $this->type);
    }
}
