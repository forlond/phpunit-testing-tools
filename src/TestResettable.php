<?php declare(strict_types=1);

namespace Forlond\TestTools;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
interface TestResettable
{
    public function reset(): void;
}
