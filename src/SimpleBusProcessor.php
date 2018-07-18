<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus;

use Interop\Queue\InvalidMessageException;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use SimpleBus\Asynchronous\Consumer\SerializedEnvelopeConsumer;
use Throwable;

class SimpleBusProcessor implements PsrProcessor
{
    private $consumer;

    public function __construct(SerializedEnvelopeConsumer $consumer)
    {
        $this->consumer = $consumer;
    }

    public function process(PsrMessage $message, PsrContext $context)
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
