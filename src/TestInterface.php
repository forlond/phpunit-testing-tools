<?php declare(strict_types=1);

namespace Forlond\TestTools;

interface TestInterface
{
    public function assert(bool $strict = true);
}
