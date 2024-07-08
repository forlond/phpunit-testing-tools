<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Workflow;

use Symfony\Component\Workflow\Event\AnnounceEvent;
use Symfony\Component\Workflow\Event\CompletedEvent;
use Symfony\Component\Workflow\Event\EnteredEvent;
use Symfony\Component\Workflow\Event\EnterEvent;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\Event\LeaveEvent;
use Symfony\Component\Workflow\Event\TransitionEvent;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;

trait TestWorkflowTrait
{
    protected function createWorkflow(?callable $configure): TestWorkflow
    {
        $builder = new TestWorkflowBuilder();
        $configure && $configure($builder);

        return $builder->build();
    }

    protected function createTransition(
        string       $name = 'transition',
        string|array $from = 'place1',
        string|array $to = 'place2',
    ): Transition {
        return new Transition($name, (array) $from, (array) $to);
    }

    protected function createMarking(Transition $transition): Marking
    {
        $from = $transition->getFroms();
        if (1 === count($from)) {
            return new Marking([$from[0] => 1]);
        }

        return new Marking(array_fill_keys($from, 1));
    }

    protected function createGuardEvent(
        object     $object,
        Transition $transition,
        ?callable  $configure = null,
    ): GuardEvent {
        $workflow = $this->createWorkflow(static function(TestWorkflowBuilder $builder) use ($configure, $transition) {
            $configure && $configure($builder);
            $builder
                ->definition
                ->addPlaces($transition->getFroms())
                ->addPlaces($transition->getTos())
                ->addTransition($transition)
            ;
        });
        $marking  = $this->createMarking($transition);
        $workflow->getMarkingStore()->setMarking($object, $marking);

        return new GuardEvent($object, $marking, $transition, $workflow);
    }

    protected function createAnnounceEvent(
        object     $object,
        Transition $transition,
        array      $context = [],
        ?callable  $configure = null,
    ): AnnounceEvent {
        $workflow = $this->createWorkflow(static function(TestWorkflowBuilder $builder) use ($configure, $transition) {
            $configure && $configure($builder);
            $builder
                ->definition
                ->addPlaces($transition->getFroms())
                ->addPlaces($transition->getTos())
                ->addTransition($transition)
            ;
        });
        $marking  = $this->createMarking($transition);
        $workflow->getMarkingStore()->setMarking($object, $marking);

        return new AnnounceEvent($object, $marking, $transition, $workflow, $context);
    }

    protected function createCompletedEvent(
        object     $object,
        Transition $transition,
        array      $context = [],
        ?callable  $configure = null,
    ): CompletedEvent {
        $workflow = $this->createWorkflow(static function(TestWorkflowBuilder $builder) use ($configure, $transition) {
            $configure && $configure($builder);
            $builder
                ->definition
                ->addPlaces($transition->getFroms())
                ->addPlaces($transition->getTos())
                ->addTransition($transition)
            ;
        });
        $marking  = $this->createMarking($transition);
        $workflow->getMarkingStore()->setMarking($object, $marking);

        return new CompletedEvent($object, $marking, $transition, $workflow, $context);
    }

    protected function createEnteredEvent(
        object     $object,
        Transition $transition,
        array      $context = [],
        ?callable  $configure = null,
    ): EnteredEvent {
        $workflow = $this->createWorkflow(static function(TestWorkflowBuilder $builder) use ($configure, $transition) {
            $configure && $configure($builder);
            $builder
                ->definition
                ->addPlaces($transition->getFroms())
                ->addPlaces($transition->getTos())
                ->addTransition($transition)
            ;
        });
        $marking  = $this->createMarking($transition);
        $workflow->getMarkingStore()->setMarking($object, $marking);

        return new EnteredEvent($object, $marking, $transition, $workflow, $context);
    }

    protected function createEnterEvent(
        object     $object,
        Transition $transition,
        array      $context = [],
        ?callable  $configure = null,
    ): EnterEvent {
        $workflow = $this->createWorkflow(static function(TestWorkflowBuilder $builder) use ($configure, $transition) {
            $configure && $configure($builder);
            $builder
                ->definition
                ->addPlaces($transition->getFroms())
                ->addPlaces($transition->getTos())
                ->addTransition($transition)
            ;
        });
        $marking  = $this->createMarking($transition);
        $workflow->getMarkingStore()->setMarking($object, $marking);

        return new EnterEvent($object, $marking, $transition, $workflow, $context);
    }

    protected function createLeaveEvent(
        object     $object,
        Transition $transition,
        array      $context = [],
        ?callable  $configure = null,
    ): LeaveEvent {
        $workflow = $this->createWorkflow(static function(TestWorkflowBuilder $builder) use ($configure, $transition) {
            $configure && $configure($builder);
            $builder
                ->definition
                ->addPlaces($transition->getFroms())
                ->addPlaces($transition->getTos())
                ->addTransition($transition)
            ;
        });
        $marking  = $this->createMarking($transition);
        $workflow->getMarkingStore()->setMarking($object, $marking);

        return new LeaveEvent($object, $marking, $transition, $workflow, $context);
    }

    protected function createTransitionEvent(
        object     $object,
        Transition $transition,
        array      $context = [],
        ?callable  $configure = null,
    ): TransitionEvent {
        $workflow = $this->createWorkflow(static function(TestWorkflowBuilder $builder) use ($configure, $transition) {
            $configure && $configure($builder);
            $builder
                ->definition
                ->addPlaces($transition->getFroms())
                ->addPlaces($transition->getTos())
                ->addTransition($transition)
            ;
        });
        $marking  = $this->createMarking($transition);
        $workflow->getMarkingStore()->setMarking($object, $marking);

        return new TransitionEvent($object, $marking, $transition, $workflow, $context);
    }
}
