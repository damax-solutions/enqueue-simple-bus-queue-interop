<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests;

use Enqueue\Null\NullContext;
use Enqueue\Null\NullMessage;
use Enqueue\SimpleBus\SimpleBusProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Asynchronous\Consumer\SerializedEnvelopeConsumer;

class SimpleBusProcessorTest extends TestCase
{
    /**
     * @var SerializedEnvelopeConsumer|MockObject
     */
    private $consumer;

    /**
     * @var SimpleBusProcessor
     */
    private $processor;

    protected function setUp()
    {
        $this->consumer = $this->createMock(SerializedEnvelopeConsumer::class);
        $this->processor = new SimpleBusProcessor($this->consumer);
    }

    /**
     * @test
     */
    public function it_consumes_message()
    {
        $this->consumer
            ->expects($this->once())
            ->method('consume')
            ->with('__message__')
        ;

        $this->assertEquals('enqueue.ack', $this->processor->process(new NullMessage('__message__'), new NullContext()));
    }
}
