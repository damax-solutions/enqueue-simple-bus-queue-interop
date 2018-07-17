<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Bridge\Symfony\Serializer;

use Enqueue\SimpleBus\Bridge\Symfony\Serializer\ObjectSerializer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\SerializerInterface;

class ObjectSerializerTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var ObjectSerializer
     */
    private $objectSerializer;

    protected function setUp()
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->objectSerializer = new ObjectSerializer($this->serializer);
    }

    /**
     * @test
     */
    public function it_serializes_object()
    {
        $object = new stdClass();

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($this->identicalTo($object), 'json')
            ->willReturn('__serialized__')
        ;

        $this->assertEquals('__serialized__', $this->objectSerializer->serialize($object));
    }

    /**
     * @test
     */
    public function it_deserializes_object()
    {
        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with('__serialized__', stdClass::class, 'json')
            ->willReturn($object = new stdClass())
        ;

        $this->assertSame($object, $this->objectSerializer->deserialize('__serialized__', stdClass::class));
    }
}
