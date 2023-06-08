<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestDeserializationVisitor implements DeserializationVisitorInterface
{
    public function __construct(
        private readonly DeserializationVisitorInterface $delegate,
    ) {
    }

    public function visitNull($data, array $type)
    {
        return $this->delegate->visitNull($data, $type);
    }

    public function visitString($data, array $type): ?string
    {
        return $this->delegate->visitString($data, $type);
    }

    public function visitBoolean($data, array $type): ?bool
    {
        return $this->delegate->visitBoolean($data, $type);
    }

    public function visitDouble($data, array $type): ?float
    {
        return $this->delegate->visitDouble($data, $type);
    }

    public function visitInteger($data, array $type): ?int
    {
        return $this->delegate->visitInteger($data, $type);
    }

    public function visitArray($data, array $type): array
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

    public function endVisitingObject(ClassMetadata $metadata, $data, array $type): object
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

    public function visitDiscriminatorMapProperty($data, ClassMetadata $metadata): string
    {
        return $this->delegate->visitDiscriminatorMapProperty($data, $metadata);
    }
}
