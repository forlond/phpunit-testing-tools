<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\Factory\JsonSerializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\SerializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\XmlSerializationVisitorFactory;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
class TestSerializationContextFactory extends AbstractTestContextFactory implements SerializationContextFactoryInterface
{
    public readonly SerializationContext $context;

    /**
     * @var array<SerializationVisitorFactory>
     */
    protected array $visitorFactories = [];

    public function __construct()
    {
        parent::__construct(new TestSerializationGraphNavigatorFactory());
        $this->context = new SerializationContext();
        $this->setVisitorFactory('json', new JsonSerializationVisitorFactory());
        $this->setVisitorFactory('xml', new XmlSerializationVisitorFactory());
    }

    public function createSerializationContext(): SerializationContext
    {
        $context = clone $this->context;
        $this->createContext($context);

        return $context;
    }

    public function getVisitorFactory(string $format): SerializationVisitorFactory
    {
        return $this->visitorFactories[$format];
    }

    public function setVisitorFactory(string $format, SerializationVisitorFactory $factory): void
    {
        $this->visitorFactories[$format] = $factory;
    }

    protected function getVisitor(): SerializationVisitorInterface
    {
        return $this->getVisitorFactory($this->format)->getVisitor();
    }

    protected function getNavigator(): GraphNavigatorInterface
    {
        if ($this->graphNavigatorFactory instanceof TestSerializationGraphNavigatorFactory) {
            $this->graphNavigatorFactory->metadataFactory = $this->metadataFactory;
        }

        return parent::getNavigator();
    }

    protected function startInitialVisiting(Context $context, object $object): void
    {
        if (!$context instanceof SerializationContext) {
            throw new \RuntimeException('The context factory expects a SerializationContext instance.');
        }

        $context->startVisiting($object);
    }
}
