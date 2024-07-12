<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Workflow;

use Forlond\TestTools\Symfony\EventDispatcher\TestEventDispatcher;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;
use Symfony\Component\Workflow\Workflow;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestWorkflow extends Workflow
{
    private readonly EventDispatcherInterface $dispatcher;

    public function __construct(
        Definition            $definition,
        MarkingStoreInterface $markingStore,
        string                $name = 'unnamed',
        ?array                $eventsToDispatch = null,
    ) {
        $this->dispatcher = new TestEventDispatcher();
        parent::__construct($definition, $markingStore, $this->dispatcher, $name, $eventsToDispatch);
    }

    public function getEventDispatcher(): TestEventDispatcher
    {
        return $this->dispatcher;
    }
}
