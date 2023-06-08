<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestSerializationVisitor implements SerializationVisitorInterface
{
    public function __construct(
        private readonly SerializationVisitorInterface $delegate,
    ) {
    }

    public function visitNull($data, array $type)
    {
        return $this->delegate->visitNull($data, $type);
    }

    public function visitString(string $data, array $type)
    {
        return $this->delegate->visitString($data, $type);
    }

    public function visitBoolean(bool $data, array $type)
    {
        return $this->delegate->visitBoolean($data, $type);
    }

    public function visitDouble(float $data, array $type)
    {
        return $this->delegate->visitDouble($data, $type);
    }

    public function visitInteger(int $data, array $type)
    {
        return $this->delegate->visitInteger($data, $type);
    }

    public function visitArray(array $data, array $type)
    {
        return $this->delegate->visitArray($data, $type);
    }

    public function startVisitingObject(ClassMetadata $metadata, object $data, array $type): void
    {
        $this->delegate->startVisitingObject($metadata, $data, $type);
    }

    public function visitProperty(PropertyMetadata $metadata, $data): void
    {
        $this->delegate->visitProperty($metadata, $data);
    }

    public function endVisitingObject(ClassMetadata $metadata, object $data, array $type)
    {
        return $this->delegate->endVisitingObject($metadata, $data, $type);
    }

    public function prepare($data)
    {
        return $this->delegate->prepare($data);
    }

    public function setNavigator(GraphNavigatorInterface $navigator): void
    {
        $this->delegate->setNavigator($navigator);
    }

    public function getResult($data)
    {
        return $this->delegate->getResult($data);
    }
}
