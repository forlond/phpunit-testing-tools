<?php declare(strict_types=1);

namespace Forlond\TestTools\Symfony\Workflow;

use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestMarkingStorage implements MarkingStoreInterface
{
    private \SplObjectStorage $storage;

    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }

    public function getMarking(object $subject): Marking
    {
        if ($this->storage->offsetExists($subject)) {
            return $this->storage->offsetGet($subject);
        }

        return new Marking();
    }

    public function setMarking(object $subject, Marking $marking, array $context = [])
    {
        $this->storage->attach($subject, $marking);
    }
}
