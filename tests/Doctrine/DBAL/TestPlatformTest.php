<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\DBAL;

use Doctrine\DBAL\Types\Types;
use Forlond\TestTools\Doctrine\DBAL\TestPlatform;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestPlatformTest extends TestCase
{
    public function testGetBooleanTypeDeclarationSQL(): void
    {
        $platform = $this->createPlatform();

        self::assertSame(Types::BOOLEAN, $platform->getBooleanTypeDeclarationSQL([]));
    }

    public function testGetIntegerTypeDeclarationSQL(): void
    {
        $platform = $this->createPlatform();

        self::assertSame(Types::INTEGER, $platform->getIntegerTypeDeclarationSQL([]));
    }

    public function testGetBigIntTypeDeclarationSQL(): void
    {
        $platform = $this->createPlatform();

        self::assertSame(Types::BIGINT, $platform->getBigIntTypeDeclarationSQL([]));
    }

    public function testGetClobTypeDeclarationSQL(): void
    {
        $platform = $this->createPlatform();

        self::assertSame(Types::TEXT, $platform->getClobTypeDeclarationSQL([]));
    }

    public function testGetBlobTypeDeclarationSQL(): void
    {
        $platform = $this->createPlatform();

        self::assertSame(Types::BLOB, $platform->getBlobTypeDeclarationSQL([]));
    }

    public function testGetSmallIntTypeDeclarationSQL(): void
    {
        $platform = $this->createPlatform();

        self::assertSame(Types::SMALLINT, $platform->getSmallIntTypeDeclarationSQL([]));
    }

    public function testGetName(): void
    {
        $platform = $this->createPlatform();

        self::assertSame('test', $platform->getName());
    }

    public function testGetCurrentDatabaseExpression(): void
    {
        $platform = $this->createPlatform();

        self::assertSame('current_database', $platform->getCurrentDatabaseExpression());
    }

    public function testSupportsIdentityColumns(): void
    {
        $platform = $this->createPlatform();

        self::assertTrue($platform->supportsIdentityColumns());
    }

    public function testSupportsSequences(): void
    {
        $platform = $this->createPlatform();

        self::assertTrue($platform->supportsSequences());
    }

    private function createPlatform(): TestPlatform
    {
        return new TestPlatform();
    }
}
