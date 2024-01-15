<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Validator;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ValidatorBuilder;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractValidatorTestCase extends TestCase
{
    protected function configureBuilder(): ValidatorBuilder
    {
        return Validation::createValidatorBuilder();
    }

    final protected function validate(
        mixed                      $value,
        Constraint|array           $constraints = null,
        GroupSequence|array|string $groups = null,
        ?callable                  $configure = null,
    ): TestConstraintViolationList {
        $builder = $this->configureBuilder();
        if ($configure) {
            $configure($builder);
        }

        $list = $builder
            ->getValidator()
            ->validate($value, $constraints, $groups)
        ;

        return new TestConstraintViolationList($list);
    }

    final protected function validateProperty(
        object                     $object,
        string                     $propertyName,
        GroupSequence|array|string $groups = null,
        ?callable                  $configure = null,
    ): TestConstraintViolationList {
        $builder = $this->configureBuilder();
        if ($configure) {
            $configure($builder);
        }

        $list = $builder
            ->getValidator()
            ->validateProperty($object, $propertyName, $groups)
        ;

        return new TestConstraintViolationList($list);
    }

    final protected function validatePropertyValue(
        object|string              $objectOrClass,
        string                     $propertyName,
        mixed                      $value,
        GroupSequence|array|string $groups = null,
        ?callable                  $configure = null,
    ): TestConstraintViolationList {
        $builder = $this->configureBuilder();
        if ($configure) {
            $configure($builder);
        }

        $list = $builder
            ->getValidator()
            ->validatePropertyValue($objectOrClass, $propertyName, $value, $groups)
        ;

        return new TestConstraintViolationList($list);
    }

    final protected function createExecutionContext(
        mixed     $root,
        ?callable $configure = null,
    ): ExecutionContextInterface {
        $builder = $this->configureBuilder();
        $factory = new TestExecutionContextFactory();
        if ($configure) {
            $configure($factory, $builder);
        }

        return $factory->createContext($builder->getValidator(), $root);
    }
}
