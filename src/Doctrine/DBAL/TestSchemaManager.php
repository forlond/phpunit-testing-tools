<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\DBAL;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\StringType;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestSchemaManager extends AbstractSchemaManager
{
    /**
     * @inheritDoc
     */
    protected function _getPortableTableColumnDefinition($tableColumn)
    {
        return new Column('test_column', new StringType());
    }
}
