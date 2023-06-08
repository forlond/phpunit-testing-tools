<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\VisitorInterface;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
final class TestGraphNavigator implements GraphNavigatorInterface
{
    public function __construct(
        private readonly GraphNavigatorInterface $delegate,
    ) {
    }

    public function initialize(VisitorInterface $visitor, Context $context): void
    {
        $this->delegate->initialize($visitor, $context);
    }

    public function accept($data, ?array $type = null)
    {
        return $this->delegate->accept($data, $type);
    }
}
