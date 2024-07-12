<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests;

use Forlond\TestTools\PHPUnit\Constraint\ArrayContains;
use Forlond\TestTools\Psr\Log\TestLogger;
use Forlond\TestTools\Symfony\EventDispatcher\TestEventDispatcher;
use Forlond\TestTools\Symfony\Form\TestForm;
use Forlond\TestTools\Symfony\Form\TestFormErrors;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormConfigBuilder;
use Symfony\Component\Form\FormError;

final class MailerTest extends TestCase
{
    public function testMama(): void
    {
        $logger = new TestLogger();

        $logger->log('critical', 'Hello', [
            'a' => [
                'c' => 'one',
                'h' => 'two',
            ],
            'b' => [
                't' => 'three',
            ],
        ]);

        $logger
            ->expect(
                'critical',
                'Hello',
                new ArrayContains([
                    'a' => new ArrayContains([
                        'c' => 'onae',
                        'h' => 'twao',
                    ]),
                    'b' => ['t' => 'threae'],
                ])
            )
            ->assert()
        ;

        $dispatcher = new TestEventDispatcher();
        $form       = new Form(new FormConfigBuilder('hello', null, $dispatcher));
        $test       = new TestForm($form);
        $form->addError(new FormError('thisa'));

        $test->isEmpty(false);
        $test->required(false);
        $test->errors(function(TestFormErrors $errors) {
            $errors
                ->expect('this')
                ->parameters(['foo' => 1])
            ;
        });
        $test->child('hello', true);
        $test->child('byeee', true);
        //$test->assert();
    }
}
