<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Connection;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDBALConnection extends Connection
{
    public function __construct(
        private readonly TestDBALDriver $driver,
    ) {
        parent::__construct(['serverVersion' => 'test'], $driver);
        $this->setNestTransactionsWithSavepoints(true);
    }

    public function setResults(...$results): void
    {
        $this->driver->connection->results = [...$results];
    }
}
