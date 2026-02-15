<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Connection;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALConnection extends Connection
{
    public string $database = 'test_database';

    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @param array<string,mixed> ...$results
     */
    public function setResult(array ...$results): void
    {
        $driver = $this->getDriver();
        assert($driver instanceof TestDBALDriver);
        $driver->connection->results = array_values($results);
    }
}
