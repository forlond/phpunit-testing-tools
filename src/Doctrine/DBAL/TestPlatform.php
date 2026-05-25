<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Types;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestPlatform extends AbstractPlatform
{
    public function getBooleanTypeDeclarationSQL(array $column): string
    {
        return Types::BOOLEAN;
    }

    public function getIntegerTypeDeclarationSQL(array $column): string
    {
        return Types::INTEGER;
    }

    public function getBigIntTypeDeclarationSQL(array $column): string
    {
        return Types::BIGINT;
    }

    public function getSmallIntTypeDeclarationSQL(array $column): string
    {
        return Types::SMALLINT;
    }

    /**
     * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _getCommonIntegerTypeDeclarationSQL(array $column): string
    {
        return '';
    }

    protected function initializeDoctrineTypeMappings(): void
    {
    }

    public function getClobTypeDeclarationSQL(array $column): string
    {
        return Types::TEXT;
    }

    public function getBlobTypeDeclarationSQL(array $column): string
    {
        return Types::BLOB;
    }

    public function getName(): string
    {
        return 'test';
    }

    public function getCurrentDatabaseExpression(): string
    {
        return 'current_database';
    }

    public function supportsIdentityColumns(): bool
    {
        return true;
    }

    public function supportsSequences(): bool
    {
        return true;
    }
}
