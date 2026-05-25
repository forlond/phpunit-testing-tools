<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestStatement implements Statement
{
    /** @var array<int,mixed>|array<string,mixed> */
    public array $params = [];

    /** @var array<int,int>|array<string,int> */
    public array $types = [];

    /**
     * @param list<array<string,mixed>> $results
     */
    public function __construct(
        public readonly string $sql,
        public readonly array  $results,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function bindValue($param, $value, $type = ParameterType::STRING): bool
    {
        $this->params[$param] = &$value;
        $this->types[$param]  = $type;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function bindParam($param, &$variable, $type = ParameterType::STRING, $length = null): bool
    {
        $this->params[$param] = &$variable;
        $this->types[$param]  = $type;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function execute($params = null): TestResult
    {
        return new TestResult($this->results, $this->sql, $params ?? $this->params);
    }
}
