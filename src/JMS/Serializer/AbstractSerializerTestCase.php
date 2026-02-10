<?php declare(strict_types=1);

namespace Forlond\TestTools\JMS\Serializer;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Type\Parser;
use JMS\Serializer\Type\Type;
use PHPUnit\Framework\TestCase;

/**
 * @author Carlos Dominguez <ixarlie@gmail.com>
 *
 * @phpstan-import-type TypeArray from Type
 */
abstract class AbstractSerializerTestCase extends TestCase
{
    private static ?Parser $typeParser = null;

    protected function createSerializer(?\Closure $configure): Serializer
    {
        $builder = new SerializerBuilder();

        if (null !== $configure) {
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

    protected function createSerializationContext(?\Closure $configure): SerializationContext
    {
        $factory = new TestSerializationContextFactory();

        if (null !== $configure) {
            $configure($factory);
        }

        return $factory->createSerializationContext();
    }

    protected function createDeserializationContext(?\Closure $configure): DeserializationContext
    {
        $factory = new TestDeserializationContextFactory();

        if (null !== $configure) {
            $configure($factory);
        }

        return $factory->createDeserializationContext();
    }

    /**
     * @return TypeArray
     */
    final protected function parseType(string $type): array
    {
        if (null === self::$typeParser) {
            self::$typeParser = new Parser();
        }

        return self::$typeParser->parse($type);
    }
}
