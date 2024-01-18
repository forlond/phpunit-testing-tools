<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractDBALTestCase extends TestCase
{
    final protected function createConnection(
        ?Configuration    $configuration = null,
        ?AbstractPlatform $platform = null,
    ): TestDBALConnection {
        return new TestDBALConnection(
            new TestDBALDriver(new TestDBALDriverConnection(), $platform ?? $this->createPlatform()),
            $configuration ?? $this->createConfiguration()
        );
    }

    protected function createConfiguration(): Configuration
    {
        return new Configuration();
    }

    protected function createPlatform(): AbstractPlatform
    {
        return new TestPlatform();
    }
}
