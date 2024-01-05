<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Form;

use Forlond\TestTools\AbstractTestGroup;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;

class TestFormErrors extends AbstractTestGroup
{
    protected const GROUP_NAME = 'form errors';

    public function __construct(
        private readonly FormErrorIterator $errors,
    ) {
    }

    public function expect(Constraint|string $message): self
    {
        $this->next();
        $this->set('message', $message, static fn(FormError $error) => $error->getMessage());

        return $this;
    }

    public function messageTemplate(Constraint|string $value): self
    {
        $this->set('messageTemplate', $value, static fn(FormError $error) => $error->getMessageTemplate());

        return $this;
    }

    public function parameters(Constraint|array $value): self
    {
        $this->set('parameters', $value, static fn(FormError $error) => $error->getMessageParameters());

        return $this;
    }

    public function pluralization(Constraint|int $value): self
    {
        $this->set('pluralization', $value, static fn(FormError $error) => $error->getMessagePluralization());

        return $this;
    }

    public function cause(mixed $value): self
    {
        $this->set('cause', $value, static fn(FormError $error) => $error->getCause());

        return $this;
    }

    protected function getValue(): array
    {
        return iterator_to_array($this->errors);
    }
}
