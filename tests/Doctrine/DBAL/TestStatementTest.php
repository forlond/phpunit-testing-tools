<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\DBAL;

use Doctrine\DBAL\ParameterType;
use Forlond\TestTools\Doctrine\DBAL\TestStatement;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestStatementTest extends TestCase
{
    public function testBindValue(): void
    {
        $statement = new TestStatement('SQL', []);

        $statement->bindValue('param', 'value');

        self::assertSame(['param' => 'value'], $statement->params);
        self::assertSame(['param' => ParameterType::STRING], $statement->types);
    }

    public function testExecute(): void
    {
        $statement = new TestStatement('SQL', []);

        $result = $statement->execute();

        self::assertSame([], $result->fetchAllAssociative());
        self::assertSame('SQL', $result->sql);
        self::assertSame([], $result->params);
    }
}
