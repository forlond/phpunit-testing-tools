<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Query;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestExceptionConverter implements ExceptionConverter
{
    /**
     * @inheritDoc
     */
    public function convert(Exception $exception, ?Query $query): DriverException
    {
        return new DriverException($exception, null);
    }
}
