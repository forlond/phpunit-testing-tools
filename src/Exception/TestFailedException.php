<?php declare(strict_types=1);

namespace Forlond\TestTools\Exception;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;

final class TestFailedException extends AssertionFailedError
{
    public function __construct(
        private readonly array   $exceptions,
        private readonly ?string $description = null,
    ) {
        $exceptions = [];
        foreach ($this->exceptions as $exception) {
            assert($exception instanceof ExpectationFailedException);
            $value = sprintf(
                '%s %s',
                $exception->toString(),
                $exception->getComparisonFailure()?->toString()
            );
            if ($this->description) {
                $value = $this->description . "\n" . $value;
            }
            $exceptions[] = $value;
        }

        parent::__construct(implode("\n\n", $exceptions));
    }
}
