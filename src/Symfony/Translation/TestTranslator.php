<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Translation;

use Forlond\TestTools\AbstractTestGroup;
use Forlond\TestTools\TestResettable;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Contracts\Translation\TranslatorTrait;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestTranslator extends AbstractTestGroup implements
    TranslatorInterface,
    LocaleAwareInterface,
    TestResettable
{
    use TranslatorTrait {
        TranslatorTrait::trans as private traitTrans;
    }

    protected const GROUP_NAME = 'translator';

    private array $calls = [];

    public function __construct(
        private readonly array $translations = [],
    ) {
    }

    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        $this->calls[] = ['id' => $id, 'params' => $parameters, 'domain' => $domain, 'locale' => $locale];

        $translation = $this->translations[$id] ?? null;
        if (null !== $translation) {
            return (string) $translation;
        }

        return $this->traitTrans($id);
    }

    public function expect(string $id): self
    {
        $this->next();
        $this->set('id', $id, static fn(array $call) => $call['id']);

        return $this;
    }

    public function parameters(Constraint|array $parameters): self
    {
        $this->set('parameters', $parameters, static fn(array $call) => $call['params']);

        return $this;
    }

    public function parameter(string $name, mixed $value): self
    {
        $this->set(
            sprintf('parameters.%s', $name),
            $value,
            static fn(array $call) => $call['params'][$name] ?? null
        );

        return $this;
    }

    public function domain(Constraint|\Stringable|string $domain): self
    {
        $this->set('domain', $domain, static fn(array $call) => $call['domain']);

        return $this;
    }

    public function locale(Constraint|\Stringable|string $locale): self
    {
        $this->set('locale', $locale, static fn(array $call) => $call['locale']);

        return $this;
    }

    public function reset(): void
    {
        $this->calls = [];
    }

    protected function getValue(): array
    {
        return $this->calls;
    }
}
