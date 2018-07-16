<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Routing;

use Enqueue\SimpleBus\Routing\FixedQueueNameResolver;
use PHPUnit\Framework\TestCase;
use stdClass;

class FixedQueueNameResolverTest extends TestCase
{
    /**
     * @test
     */
    public function it_resolves_queue_name()
    {
        $msg = new stdClass();

        $this->assertEquals('__queue__', (new FixedQueueNameResolver('__queue__'))->resolveRoutingKeyFor($msg));
    }
}
