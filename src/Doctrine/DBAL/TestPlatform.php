<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Types;

final class TestPlatform extends AbstractPlatform
{
    public function getBooleanTypeDeclarationSQL(array $column)
    {
        return Types::BOOLEAN;
    }

    public function getIntegerTypeDeclarationSQL(array $column)
    {
        return Types::INTEGER;
    }

    public function getBigIntTypeDeclarationSQL(array $column)
    {
        return Types::BIGINT;
    }

    public function getSmallIntTypeDeclarationSQL(array $column)
    {
        return Types::SMALLINT;
    }

    protected function _getCommonIntegerTypeDeclarationSQL(array $column)
    {
        return '';
    }

    protected function initializeDoctrineTypeMappings()
    {
    }

    public function getClobTypeDeclarationSQL(array $column)
    {
        return Types::TEXT;
    }

    public function getBlobTypeDeclarationSQL(array $column)
    {
        return Types::BLOB;
    }

    public function getName()
    {
        return 'test';
    }

    public function getCurrentDatabaseExpression(): string
    {
        return 'current_database';
    }
}
