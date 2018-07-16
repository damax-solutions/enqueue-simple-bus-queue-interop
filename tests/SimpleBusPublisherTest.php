<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests;

use Enqueue\SimpleBus\Routing\FixedQueueNameResolver;
use Enqueue\SimpleBus\SimpleBusPublisher;
use Enqueue\SimpleBus\Tests\Interop\Context;
use Enqueue\SimpleBus\Tests\Interop\SpyProducer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Serialization\Envelope\Serializer\MessageInEnvelopeSerializer;
use stdClass;

class SimpleBusPublisherTest extends TestCase
{
    /**
     * @var MessageInEnvelopeSerializer|MockObject
     */
    private $serializer;

    /**
     * @var SpyProducer
     */
    private $producer;

    /**
     * @var SimpleBusPublisher
     */
    private $publisher;

    protected function setUp()
    {
        $this->serializer = $this->createMock(MessageInEnvelopeSerializer::class);
        $this->producer = new SpyProducer();
        $this->publisher = new SimpleBusPublisher($this->serializer, new FixedQueueNameResolver('__queue__'), new Context($this->producer));
    }

    /**
     * @test
     */
    public function it_publishes_message()
    {
        $message = new stdClass();

        $this->serializer
            ->expects($this->once())
            ->method('wrapAndSerialize')
            ->with($this->identicalTo($message))
            ->willReturn('__serialized__')
        ;

        $this->publisher->publish($message);

        // Spy values.
        $this->assertEquals('__queue__', $this->producer->getQueue()->getQueueName());
        $this->assertEquals('__serialized__', $this->producer->getMessage()->getBody());
    }
}
