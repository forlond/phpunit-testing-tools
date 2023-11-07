<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Validator;

use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractAdvancedPropertyValidatorTestCase extends AbstractPropertyValidatorTestCase
{
    abstract protected function getObject(): object;

    abstract public function dataPropertyValidatorProvider(): \Generator;

    /**
     * @dataProvider dataPropertyValidatorProvider
     */
    final public function testPropertyValidations(
        string                          $propertyName,
        mixed                           $value,
        string|GroupSequence|array|null $groups,
        callable|string|Constraint|null $assertions = null,
        ?callable                       $objectEdition = null,
    ): void {
        $object = $this->getObject();
        if ($objectEdition) {
            $objectEdition($object);
        }

        $list = $this->validatePropertyValue($object, $propertyName, $value, $groups);

        if ($assertions) {
            if (is_callable($assertions)) {
                $assertions($list);
            } else {
                $list->expect($assertions);
            }
        }

        $list->assert();
    }
}
