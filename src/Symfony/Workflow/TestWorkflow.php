<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Workflow;

use Forlond\TestTools\Symfony\EventDispatcher\TestEventDispatcher;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;
use Symfony\Component\Workflow\Workflow;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestWorkflow extends Workflow
{
    public readonly TestEventDispatcher $dispatcher;

    public function __construct(
        Definition            $definition,
        MarkingStoreInterface $markingStore,
        string                $name = 'unnamed',
        ?array                $eventsToDispatch = null,
    ) {
        $this->dispatcher = new TestEventDispatcher();
        parent::__construct($definition, $markingStore, $this->dispatcher, $name, $eventsToDispatch);
    }
}
