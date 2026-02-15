<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Driver\Result;

/**
 * @author Carlos Domínguez <ixarlie@gmail.com>
 */
final class TestResult implements Result
{
    private int $pointer = 0;

    /**
     * @param list<array<string,mixed>> $data
     */
    public function __construct(
        private readonly array  $data,
        public readonly ?string $sql = null,
        public readonly ?array  $params = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function fetchNumeric(): array|false
    {
        $row = $this->fetchAssociative();
        if ($row === false) {
            return false;
        }

        return array_values($row);
    }

    /**
     * @inheritDoc
     */
    public function fetchAssociative(): array|false
    {
        return $this->data[$this->pointer++] ?? false;
    }

    /**
     * @inheritDoc
     */
    public function fetchOne(): mixed
    {
        $row = $this->fetchNumeric();
        if ($row === false) {
            return false;
        }

        return $row[0];
    }

    /**
     * @inheritDoc
     */
    public function fetchAllNumeric(): array
    {
        return array_map(array_values(...), $this->data);
    }

    /**
     * @inheritDoc
     */
    public function fetchAllAssociative(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function fetchFirstColumn(): array
    {
        return array_map(static fn(array $row) => reset($row), $this->data);
    }

    /**
     * @inheritDoc
     */
    public function rowCount(): int
    {
        return count($this->data);
    }

    /**
     * @inheritDoc
     */
    public function columnCount(): int
    {
        $row = $this->data[0] ?? null;

        return null !== $row ? count($row) : 0;
    }

    /**
     * @inheritDoc
     */
    public function free(): void
    {
        $this->pointer = count($this->data);
    }
}
