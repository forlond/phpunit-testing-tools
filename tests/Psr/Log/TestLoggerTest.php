<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Psr\Log;

use Forlond\TestTools\Exception\TestFailedException;
use Forlond\TestTools\Psr\Log\TestLogger;
use PHPUnit\Framework\TestCase;

final class TestLoggerTest extends TestCase
{
    public function testSimpleAssert(): void
    {
        $logger = new TestLogger();
        $logger->info('message', ['foobar' => true]);

        $logger
            ->expect('info', 'message', ['foobar' => true])
            ->assert()
        ;
    }

    public function testDisableStrictSize(): void
    {
        $logger = new TestLogger();
        $logger->info('message', ['foobar' => true]);
        $logger->info('other');

        $logger
            ->disableStrictSize()
            ->expect('info', 'message', ['foobar' => true])
            ->assert()
        ;
    }

    public function testDisableStrictSequence(): void
    {
        $logger = new TestLogger();
        $logger->info('message', ['foobar' => true]);
        $logger->info('other');

        $logger
            ->disableStrictSequence()
            ->expect('info', 'other', null)
            ->expect('info', 'message', ['foobar' => true])
            ->assert()
        ;
    }

    public function testDisableStrictSequenceAndStrictSize(): void
    {
        $logger = new TestLogger();
        $logger->info('other');
        $logger->info('message', ['foobar' => true]);

        $logger
            ->disableStrictSequence()
            ->disableStrictSize()
            ->expect('info', 'message', ['foobar' => true])
            ->assert()
        ;
    }

    public function testExpectationCannotBeFound(): void
    {
        $this->expectException(TestFailedException::class);
        $this->expectExceptionMessage(
            <<<EOF
Failed asserting that the logger contains an element at index 1 that matches the following constraint(s):
1.level
Failed asserting that two strings are identical.
--- Expected
+++ Actual
@@ @@
-'warning'
+'info'


1.message
Failed asserting that two strings are identical.
--- Expected
+++ Actual
@@ @@
-'other'
+'message'
EOF
        );

        $logger = new TestLogger();
        $logger->info('message', ['foobar' => true]);

        $logger
            ->expect('warning', 'other', null)
            ->assert()
        ;
    }
}
