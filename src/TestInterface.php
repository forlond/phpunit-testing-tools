<?php declare(strict_types=1);

namespace Forlond\TestTools;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
interface TestInterface
{
    public function assert(bool $strict = true): void;
}
