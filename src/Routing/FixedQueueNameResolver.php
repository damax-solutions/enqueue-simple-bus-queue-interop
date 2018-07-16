<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Routing;

use SimpleBus\Asynchronous\Routing\RoutingKeyResolver;

final class FixedQueueNameResolver implements RoutingKeyResolver
{
    private $queueName;

    public function __construct(string $queueName)
    {
        $this->queueName = $queueName;
    }

    public function resolveRoutingKeyFor($message): string
    {
        return $this->queueName;
    }
}
