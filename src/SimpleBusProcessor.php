<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus;

use Interop\Queue\Context;
use Interop\Queue\Exception\InvalidMessageException;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use SimpleBus\Asynchronous\Consumer\SerializedEnvelopeConsumer;
use Throwable;

final class SimpleBusProcessor implements Processor
{
    private $consumer;

    public function __construct(SerializedEnvelopeConsumer $consumer)
    {
        $this->consumer = $consumer;
    }

    public function process(Message $message, Context $context)
    {
        try {
            $this->consumer->consume($message->getBody());

            $result = self::ACK;
        } catch (InvalidMessageException $e) {
            $result = self::REJECT; // Reject invalid messages.
        } catch (Throwable $e) {
            $result = self::REQUEUE; // Do not loose messages when problem occurs e.g. for Redis transport.
        }

        return $result;
    }
}
