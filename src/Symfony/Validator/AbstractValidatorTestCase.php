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
    final protected function validate(
        mixed                      $value,
        Constraint|array           $constraints = null,
        GroupSequence|array|string $groups = null,
        ?callable                  $configure = null,
    ): TestConstraintViolationList {
        $builder = $this->createBuilder();
        if ($configure) {
            $configure($builder);
        }

        $validator = $builder->getValidator();
        $list      = $validator->validate($value, $constraints, $groups);

        return new TestConstraintViolationList($list);
    }

    final protected function createExecutionContext(
        mixed     $root,
        ?callable $configure = null,
    ): ExecutionContextInterface {
        $builder = $this->createBuilder();
        $factory = new TestExecutionContextFactory();
        if ($configure) {
            $configure($factory, $builder);
        }

        $validator = $builder->getValidator();

        return $factory->createContext($validator, $root);
    }

    protected function createBuilder(): ValidatorBuilder
    {
        return Validation::createValidatorBuilder();
    }
}
