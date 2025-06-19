<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Validator;

use Forlond\TestTools\Symfony\Translation\TestTranslator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Context\ExecutionContextFactoryInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
            $translator = new TestTranslator();
            $translator->setLocale('en');
        }

        return new ExecutionContext($validator, $root, $translator, $this->translationDomain);
    }
}
