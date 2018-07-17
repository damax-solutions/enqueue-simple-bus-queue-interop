<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Routing;

use SimpleBus\Asynchronous\Routing\RoutingKeyResolver;

final class MappedQueueNameResolver implements RoutingKeyResolver
{
    private $map;
    private $default;

    public function __construct(array $map, string $default)
    {
        $this->map = $map;
        $this->default = $default;
    }

    public function resolveRoutingKeyFor($message): string
    {
        return $this->map[get_class($message)] ?? $this->default;
    }
}
