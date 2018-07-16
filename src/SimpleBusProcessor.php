<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus;

use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use SimpleBus\Asynchronous\Consumer\SerializedEnvelopeConsumer;

class SimpleBusProcessor implements PsrProcessor
{
    private $consumer;

    public function __construct(SerializedEnvelopeConsumer $consumer)
    {
        $this->consumer = $consumer;
    }

    public function process(PsrMessage $message, PsrContext $context)
    {
        $this->consumer->consume($message->getBody());

        return self::ACK;
    }
}
