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
    protected function createConnection(Configuration $configuration, AbstractPlatform $platform): TestDBALConnection
    {
        $connection         = new TestDBALDriverConnection();
        $exceptionConverter = new TestExceptionConverter();

        $driver = new TestDBALDriver($connection, $exceptionConverter, $platform);
        foreach ($configuration->getMiddlewares() as $middleware) {
            $driver = $middleware->wrap($driver);
        }

        return new TestDBALConnection(new TestDBALDriver($connection, $exceptionConverter, $platform, $driver));
    }
}
