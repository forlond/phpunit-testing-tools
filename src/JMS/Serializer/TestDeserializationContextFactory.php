<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\ContextFactory\DeserializationContextFactoryInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\Factory\DeserializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\JsonDeserializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\XmlDeserializationVisitorFactory;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
class TestDeserializationContextFactory extends AbstractTestContextFactory implements
    DeserializationContextFactoryInterface
{
    public readonly DeserializationContext $context;

    /**
     * @var array<DeserializationVisitorFactory>
     */
    protected array $visitorFactories = [];

    public function __construct()
    {
        parent::__construct(new TestDeserializationGraphNavigatorFactory());
        $this->context = new DeserializationContext();
        $this->setVisitorFactory('json', new JsonDeserializationVisitorFactory());
        $this->setVisitorFactory('xml', new XmlDeserializationVisitorFactory());
    }

    public function createDeserializationContext(): DeserializationContext
    {
        $context = clone $this->context;
        $this->createContext($context);

        return $context;
    }

    public function getVisitorFactory(string $format): DeserializationVisitorFactory
    {
        return $this->visitorFactories[$format];
    }

    public function setVisitorFactory(string $format, DeserializationVisitorFactory $factory): void
    {
        $this->visitorFactories[$format] = $factory;
    }

    protected function getVisitor(): DeserializationVisitorInterface
    {
        return $this->getVisitorFactory($this->format)->getVisitor();
    }

    protected function getNavigator(): GraphNavigatorInterface
    {
        if ($this->graphNavigatorFactory instanceof TestDeserializationGraphNavigatorFactory) {
            $this->graphNavigatorFactory->metadataFactory = $this->metadataFactory;
        }

        return parent::getNavigator();
    }

    protected function startInitialVisiting(Context $context, object $object): void
    {
        if (!$context instanceof DeserializationContext) {
            throw new \RuntimeException('The context factory expects a DeserializationContext instance.');
        }

        $context->increaseDepth();
    }
}
