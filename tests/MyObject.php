<?php declare(strict_types=1);

namespace Forlond\TestTools\Tests;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
#[
    ORM\Entity
]
class MyObject
{
    #[
        ORM\Id,
        ORM\Column(name: "id", type: "integer"),
        ORM\GeneratedValue
    ]
    public ?int $id;

    #[
        ORM\Column(type: "string"),
    ]
    public ?string $color;
}
