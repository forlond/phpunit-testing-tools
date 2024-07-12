<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Workflow;

use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestWorkflowBuilder
{
    public string $name = 'unnamed';

    public readonly DefinitionBuilder $definition;

    public ?MarkingStoreInterface $markingStore = null;

    public ?array $eventsToDispatch = null;

    public function __construct()
    {
        $this->definition = new DefinitionBuilder();
    }

    public function build(): TestWorkflow
    {
        return new TestWorkflow(
            $this->definition->build(),
            $this->markingStore ?? new TestMarkingStorage(),
            $this->name,
            $this->eventsToDispatch
        );
    }
}
