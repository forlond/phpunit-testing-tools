<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\StringType;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 *
 * @extends AbstractSchemaManager<AbstractPlatform>
 */
final class TestSchemaManager extends AbstractSchemaManager
{
    public ?Column $column = null;

    /**
     * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _getPortableTableColumnDefinition($tableColumn): Column
    {
        return $this->column ?? new Column('test_column', new StringType());
    }
}
