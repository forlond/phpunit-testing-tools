<?php declare(strict_types=1);

namespace Forlond\TestTools;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
interface TestConstraintInterface
{
    public function evaluate(mixed $other): bool;
}
