<?php declare(strict_types=1);

namespace Forlond\TestTools;

use Forlond\TestTools\Exception\TestFailedException;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
interface TestInterface
{
    /**
     * @throws TestFailedException
     */
    public function assert(): void;
}
