<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALConnection extends Connection
{
    public string $database = 'test_database';

    /**
     */
    public function __construct(
        private readonly TestDBALDriver $driver,
        ?Configuration                  $configuration = null,
    ) {
        try {
            parent::__construct(['serverVersion' => 'test'], $driver, $configuration);
            $this->setNestTransactionsWithSavepoints(true);
        } catch (Exception) {
        }
    }

    public function getDatabase()
    {
        try {
            $this->setResults([$this->database]);

            return parent::getDatabase();
        } catch (Exception) {
            return null;
        } finally {
            $this->setResults();
        }
    }

    public function setResults(...$results): void
    {
        $this->driver->connection->results = [...$results];
    }
}
