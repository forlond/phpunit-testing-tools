<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Exception\DriverException;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALConnection extends Connection
{
    public string $database = 'test_database';

    /**
     * @phpstan-ignore parameter.missing, method.childParameterType, parameter.missing, method.childParameterType
     */
    public function __construct(
        TestDBALDriver $driver,
        ?Configuration $config = null,
    ) {
        parent::__construct([], $driver, $config);
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getDriver(): TestDBALDriver
    {
        $driver = parent::getDriver();
        assert($driver instanceof TestDBALDriver);

        return $driver;
    }

    public function createSchemaManager(): TestSchemaManager
    {
        $manager = parent::createSchemaManager();
        assert($manager instanceof TestSchemaManager);

        return $manager;
    }

    /**
     * @param array<string,mixed> ...$results
     */
    public function setResults(array ...$results): void
    {
        $this->getDriver()->connection->results = array_values($results);
    }

    public function setException(DriverException|Exception $e): void
    {
        if (!$e instanceof DriverException) {
            $e = new DriverException($e, null);
        }

        $this->getDriver()->exceptionConverter->exception = $e;
    }
}
