<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractDBALTestCase extends TestCase
{
    final protected function createConnection(?AbstractPlatform $platform = null): TestDBALConnection
    {
        return new TestDBALConnection(
            new TestDBALDriver(new TestDBALDriverConnection(), $platform ?? $this->getPlatform())
        );
    }

    protected function getPlatform(): AbstractPlatform
    {
        return new TestPlatform();
    }
}
