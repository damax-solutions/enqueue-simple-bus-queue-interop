<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Interop;

use Enqueue\Null\NullProducer;
use Interop\Queue\PsrDestination;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrQueue;

class SpyProducer extends NullProducer
{
    private $queue;
    private $message;

    public function send(PsrDestination $queue, PsrMessage $message): void
    {
        $this->queue = $queue;
        $this->message = $message;
    }

    public function getQueue(): PsrQueue
    {
        return $this->queue;
    }

    public function getMessage(): PsrMessage
    {
        return $this->message;
    }
}
