<?php declare(strict_types=1);

namespace Forlond\TestTools;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\SelfDescribing;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
interface TestConstraintInterface extends SelfDescribing
{
    /**
     * @throws ExpectationFailedException
     */
    public function evaluate(mixed $other, bool $returnResult = false): ?bool;
}
