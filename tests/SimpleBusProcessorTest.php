<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests;

use Enqueue\Null\NullContext;
use Enqueue\Null\NullMessage;
use Enqueue\SimpleBus\SimpleBusProcessor;
use Interop\Queue\Exception\InvalidMessageException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
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

        $result = $this->processor->process(new NullMessage('__message__'), new NullContext());

        $this->assertEquals('enqueue.ack', $result);
    }

    /**
     * @test
     */
    public function it_rejects_message()
    {
        $this->consumer
            ->method('consume')
            ->willThrowException(new InvalidMessageException())
        ;

        $result = $this->processor->process(new NullMessage('__message__'), new NullContext());

        $this->assertEquals('enqueue.reject', $result);
    }

    /**
     * @test
     */
    public function it_requeues_message()
    {
        $this->consumer
            ->method('consume')
            ->willThrowException(new RuntimeException())
        ;

        $result = $this->processor->process(new NullMessage('__message__'), new NullContext());

        $this->assertEquals('enqueue.requeue', $result);
    }
}
