<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Interop;

use Enqueue\Null\NullProducer;
use Interop\Queue\Destination;
use Interop\Queue\Message;
use Interop\Queue\Queue;

class SpyProducer extends NullProducer
{
    private $queue;
    private $message;

    public function send(Destination $queue, Message $message): void
    {
        $this->queue = $queue;
        $this->message = $message;
    }

    public function getQueue(): Queue
    {
        return $this->queue;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
