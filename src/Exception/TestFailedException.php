<?php declare(strict_types=1);

namespace Forlond\TestTools\Exception;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestFailedException extends AssertionFailedError
{
    public function __construct(
        private readonly array   $exceptions,
        private readonly ?string $description = null,
    ) {
        $exceptions = [];
        foreach ($this->exceptions as $exception) {
            assert($exception instanceof ExpectationFailedException);
            $value = $exception->toString();
            $value .= $exception->getComparisonFailure()?->toString() ?? "\n";
            if ($this->description) {
                $value = $this->description . "\n" . $value;
            }
            $exceptions[] = $value;
        }

        parent::__construct(trim(implode("\n\n", $exceptions)));
    }
}
