<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Interop;

use Enqueue\Null\NullContext;
use Interop\Queue\Producer;

class Context extends NullContext
{
    private $producer;

    public function __construct(Producer $producer)
    {
        $this->producer = $producer;
    }

    public function createProducer(): Producer
    {
        return $this->producer;
    }
}
