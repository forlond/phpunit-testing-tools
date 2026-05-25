<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Forlond\TestTools\Doctrine\DBAL\TestDBALConnection;
use Forlond\TestTools\Doctrine\DBAL\TestDBALDriver;
use Forlond\TestTools\Doctrine\DBAL\TestPlatform;
use Forlond\TestTools\Doctrine\ORM\TestEntityManager;
use Forlond\TestTools\Tests\Fixtures\Doctrine\DoctrineEntity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestEntityManagerTest extends TestCase
{
    public function testGetRepository(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('getRepository')
                ->with(DoctrineEntity::class)
            ;
        });

        $em->getRepository(DoctrineEntity::class);
    }

    public function testGetMetadataFactory(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('getMetadataFactory')
            ;
        });

        $em->getMetadataFactory();
    }

    public function testGetClassMetadata(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('getClassMetadata')
                ->with(DoctrineEntity::class)
            ;
        });

        $em->getClassMetadata(DoctrineEntity::class);
    }

    public function testGetConnection(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('getConnection')
                ->willReturn(new TestDBALConnection(new TestDBALDriver(new TestPlatform())))
            ;
        });

        $em->getConnection();
    }

    public function testGetExpressionBuilder(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('getExpressionBuilder')
            ;
        });

        $em->getExpressionBuilder();
    }

    public function testBeginTransaction(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('beginTransaction')
            ;
        });

        $em->beginTransaction();
    }

    public function testCommit(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('commit')
            ;
        });

        $em->commit();
    }

    public function testRollback(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('rollback')
            ;
        });

        $em->rollback();
    }

    public function testCreateQuery(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('createQuery')
                ->with('')
            ;
        });

        $em->createQuery();
    }

    public function testCreateNativeQuery(): void
    {
        $rsm = new ResultSetMapping();

        $em = $this->createEntityManager(function(MockObject $delegated) use ($rsm) {
            $delegated
                ->expects($this->once())
                ->method('createNativeQuery')
                ->with('sql', $rsm)
            ;
        });

        $em->createNativeQuery('sql', $rsm);
    }

    public function testCreateQueryBuilder(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('createQueryBuilder')
            ;
        });

        $em->createQueryBuilder();
    }

    public function testGetReference(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('getReference')
                ->with(DoctrineEntity::class, 1)
            ;
        });

        $em->getReference(DoctrineEntity::class, 1);
    }

    public function testClose(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('close')
            ;
        });

        $em->close();
    }

    public function testLock(): void
    {
        $entity = new DoctrineEntity();

        $em = $this->createEntityManager(function(MockObject $delegated) use ($entity) {
            $delegated
                ->expects($this->once())
                ->method('lock')
                ->with($entity, 0, null)
            ;
        });

        $em->lock($entity, 0);
    }

    public function testFind(): void
    {
        $em = $this->createEntityManager(function(MockObject $delegated) {
            $delegated
                ->expects($this->once())
                ->method('find')
                ->with(DoctrineEntity::class, 1)
            ;
        });

        $em->find(DoctrineEntity::class, 1);
    }

    private function createEntityManager(?callable $configure): TestEntityManager
    {
        $delegated = $this->createMock(EntityManagerInterface::class);

        if (null !== $configure) {
            $configure($delegated);
        }

        return new TestEntityManager($delegated);
    }
}
