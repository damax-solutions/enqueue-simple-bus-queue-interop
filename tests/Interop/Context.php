<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Interop;

use Enqueue\Null\NullContext;
use Interop\Queue\PsrProducer;

class Context extends NullContext
{
    private $producer;

    public function __construct(PsrProducer $producer)
    {
        $this->producer = $producer;
    }

    public function createProducer(): PsrProducer
    {
        return $this->producer;
    }
}
