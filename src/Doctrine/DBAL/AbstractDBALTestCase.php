<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractDBALTestCase extends TestCase
{
    protected function createConnection(AbstractPlatform $platform): TestDBALConnection
    {
        return new TestDBALConnection(new TestDBALDriver(new TestDBALDriverConnection(), $platform));
    }
}
