<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Cache\ArrayResult;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\ParameterType;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALDriverConnection implements Connection
{
    public array $results = [];

    public function prepare(string $sql): TestStatement
    {
        return new TestStatement($this->results);
    }

    public function query(string $sql): Result
    {
        return new ArrayResult($this->results);
    }

    public function quote($value, $type = ParameterType::STRING)
    {
        return $value;
    }

    public function exec(string $sql): int
    {
        return 1;
    }

    public function lastInsertId($name = null)
    {
        return 1;
    }

    public function beginTransaction()
    {
        return true;
    }

    public function commit()
    {
        return true;
    }

    public function rollBack()
    {
        return true;
    }

    public function getNativeConnection()
    {
        return $this;
    }
}
