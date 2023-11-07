<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Validator;

use Forlond\TestTools\PHPUnit\Constraint\TraversableContainsCallback;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestConstraintViolationList
{
    private array $expects = [];

    public function __construct(
        private readonly ConstraintViolationListInterface $list,
    ) {
    }

    public function expect(Constraint|string $message): TestConstraintValidatorConfigurator
    {
        $configurator    = new TestConstraintValidatorConfigurator($message, $this);
        $this->expects[] = $configurator;

        return $configurator;
    }

    public function assert(bool $strict = true): void
    {
        if ($strict) {
            Assert::assertCount(count($this->expects), $this->list, __CLASS__);
        }

        $violations = clone $this->list;
        foreach ($this->expects as $expect) {
            Assert::assertThat(
                $violations,
                new TraversableContainsCallback(
                    $expect,
                    static function(
                        TestConstraintValidatorConfigurator $expect,
                        ConstraintViolationInterface        $violation,
                        int                                 $index,
                    ) use ($violations): bool {
                        if (!$expect->matches($violation)) {
                            return false;
                        }

                        // When the expected matches, then removes the violation from the main list.
                        $violations->remove($index);

                        return true;
                    }
                ),
                __CLASS__
            );
        }
    }
}
