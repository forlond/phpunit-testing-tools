<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Validator;

use Symfony\Component\Validator\Constraints\GroupSequence;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractPropertyValidatorTestCase extends AbstractValidatorTestCase
{
    final protected function validateProperty(
        object                     $object,
        string                     $propertyName,
        string|GroupSequence|array $groups = null,
        ?callable                  $configure = null,
    ): TestConstraintViolationList {
        $builder = $this->createBuilder();
        if ($configure) {
            $configure($builder);
        }

        $validator = $builder->getValidator();
        $list      = $validator->validateProperty($object, $propertyName, $groups);

        return new TestConstraintViolationList($list);
    }

    final protected function validatePropertyValue(
        object                     $object,
        string                     $propertyName,
        mixed                      $value,
        string|GroupSequence|array $groups = null,
        ?callable                  $configure = null,
    ): TestConstraintViolationList {
        $builder = $this->createBuilder();
        if ($configure) {
            $configure($builder);
        }

        $validator = $builder->getValidator();
        $list      = $validator->validatePropertyValue($object, $propertyName, $value, $groups);

        return new TestConstraintViolationList($list);
    }
}
