<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\DBAL;

use Doctrine\DBAL\Driver\PDO\Exception as PDOException;
use Doctrine\DBAL\Exception\ConnectionLost;
use Forlond\TestTools\Doctrine\DBAL\TestExceptionConverter;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestExceptionConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $exception = new ConnectionLost(new PDOException('Error'), null);
        $converter = $this->createExceptionConverter();

        $result = $converter->convert($exception, null);

        self::assertNotSame($exception, $result);
        self::assertSame($exception, $result->getPrevious());
    }

    public function testConvertWithConfigured(): void
    {
        $exception = new ConnectionLost(new PDOException('Error'), null);
        $converter = $this->createExceptionConverter();

        $converter->exception = $exception;

        $result = $converter->convert($exception, null);

        self::assertSame($exception, $result);
    }

    private function createExceptionConverter(): TestExceptionConverter
    {
        return new TestExceptionConverter();
    }
}
