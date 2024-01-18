<?php declare(strict_types=1);

namespace Forlond\TestTools\Doctrine\ORM;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Forlond\TestTools\Doctrine\DBAL\TestDBALConnection;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestEntityManager extends EntityManagerDecorator
{
    public function getConnection(): TestDBALConnection
    {
        $connection = parent::getConnection();
        if (!$connection instanceof TestDBALConnection) {
            throw new \LogicException('Invalid configured connection.');
        }

        return $connection;
    }
}
