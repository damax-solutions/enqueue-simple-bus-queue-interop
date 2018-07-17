<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Routing;

use Enqueue\SimpleBus\Routing\MappedQueueNameResolver;
use PHPUnit\Framework\TestCase;
use stdClass;

class MappedQueueNameResolverTest extends TestCase
{
    /**
     * @var MappedQueueNameResolver
     */
    private $resolver;

    protected function setUp()
    {
        $map = [
            Foo::class => 'foo_queue',
            Bar::class => 'bar_queue',
        ];

        $this->resolver = new MappedQueueNameResolver($map, 'default_queue');
    }

    /**
     * @test
     */
    public function it_resolves_queue_for_message()
    {
        $this->assertEquals('foo_queue', $this->resolver->resolveRoutingKeyFor(new Foo()));
        $this->assertEquals('bar_queue', $this->resolver->resolveRoutingKeyFor(new Bar()));
    }

    /**
     * @test
     */
    public function it_fallbacks_to_default_queue()
    {
        $this->assertEquals('default_queue', $this->resolver->resolveRoutingKeyFor(new stdClass()));
    }
}

class Foo
{
}

class Bar
{
}
