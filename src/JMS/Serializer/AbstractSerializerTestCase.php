<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Type\Parser;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 */
abstract class AbstractSerializerTestCase extends TestCase
{
    private static ?Parser $typeParser = null;

    protected function createSerializer(?callable $configure): Serializer
    {
        $builder = new SerializerBuilder();

        if ($configure) {
            $configure($builder);
        } else {
            $builder
                ->addDefaultHandlers()
                ->addDefaultListeners()
                ->addDefaultSerializationVisitors()
                ->addDefaultDeserializationVisitors()
            ;
        }

        return $builder->build();
    }

    protected function createSerializationContext(?callable $configure): SerializationContext
    {
        $factory = new TestSerializationContextFactory();

        $configure && $configure($factory);

        return $factory->createSerializationContext();
    }

    protected function createDeserializationContext(?callable $configure): DeserializationContext
    {
        $factory = new TestDeserializationContextFactory();

        $configure && $configure($factory);

        return $factory->createDeserializationContext();
    }

    final protected function parseType(string $type): array
    {
        if (null === static::$typeParser) {
            static::$typeParser = new Parser();
        }

        return static::$typeParser->parse($type);
    }
}
