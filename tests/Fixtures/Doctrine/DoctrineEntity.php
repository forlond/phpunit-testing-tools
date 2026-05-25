<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests\Fixtures\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class DoctrineEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;
}
