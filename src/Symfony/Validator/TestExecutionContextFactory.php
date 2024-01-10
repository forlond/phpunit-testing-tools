<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Validator;

use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Context\ExecutionContextFactoryInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Contracts\Translation\TranslatorTrait;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestExecutionContextFactory implements ExecutionContextFactoryInterface
{
    public ?TranslatorInterface $translator = null;

    public ?string $translationDomain = null;

    public function createContext(ValidatorInterface $validator, mixed $root): ExecutionContextInterface
    {
        $translator = $this->translator;
        if (null === $translator) {
            $translator = new class() implements TranslatorInterface, LocaleAwareInterface {
                use TranslatorTrait;
            };
            $translator->setLocale('en');
        }

        return new ExecutionContext($validator, $root, $translator, $this->translationDomain);
    }
}
