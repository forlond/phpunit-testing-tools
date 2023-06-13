<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Cache\ArrayResult;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestStatement implements Statement
{
    public array $bindings = [];

    public function __construct(
        private readonly array $results,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function bindValue($param, $value, $type = ParameterType::STRING)
    {
        $this->bindings[] = func_get_args();
    }

    /**
     * @inheritDoc
     */
    public function bindParam($param, &$variable, $type = ParameterType::STRING, $length = null)
    {
        $this->bindings[] = func_get_args();
    }

    /**
     * @inheritDoc
     */
    public function execute($params = null): Result
    {
        return new ArrayResult($this->results);
    }

    public function reset(): void
    {
        $this->bindings = [];
    }
}
