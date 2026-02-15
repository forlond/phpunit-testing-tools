<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALDriverConnection implements Connection
{
    /**
     * @var list<array<string,mixed>>
     */
    public array $results = [];

    public function prepare(string $sql): TestStatement
    {
        return new TestStatement($sql, $this->results);
    }

    public function query(string $sql): TestResult
    {
        return new TestResult($this->results, $sql);
    }

    public function quote($value, $type = ParameterType::STRING): string
    {
        return $value;
    }

    public function exec(string $sql): int
    {
        return 1;
    }

    public function lastInsertId($name = null): int
    {
        return 1;
    }

    public function beginTransaction(): bool
    {
        return true;
    }

    public function commit(): bool
    {
        return true;
    }

    public function rollBack(): bool
    {
        return true;
    }

    public function getNativeConnection(): object
    {
        return $this;
    }
}
