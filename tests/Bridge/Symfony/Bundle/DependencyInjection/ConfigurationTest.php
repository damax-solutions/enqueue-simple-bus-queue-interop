<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Enqueue\SimpleBus\Bridge\Symfony\Bundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @test
     */
    public function it_processes_empty_config()
    {
        $config = [];

        $this->assertProcessedConfigurationEquals([$config], [
            'commands' => [
                'enabled' => false,
                'transport_name' => 'default',
                'default_queue' => 'asynchronous_commands',
                'queue_map' => [],
                'processor_service_id' => 'enqueue.simple_bus.commands_processor',
            ],
            'events' => [
                'enabled' => false,
                'transport_name' => 'default',
                'default_queue' => 'asynchronous_events',
                'queue_map' => [],
                'processor_service_id' => 'enqueue.simple_bus.events_processor',
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_processes_simplified_commands_config()
    {
        $config = [
            'commands' => 'async_commands',
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'commands' => [
                'enabled' => true,
                'transport_name' => 'default',
                'default_queue' => 'async_commands',
                'queue_map' => [],
                'processor_service_id' => 'enqueue.simple_bus.commands_processor',
            ],
        ], 'commands');
    }

    /**
     * @test
     */
    public function it_processes_simplified_events_config()
    {
        $config = [
            'events' => 'domain_events',
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'events' => [
                'enabled' => true,
                'transport_name' => 'default',
                'default_queue' => 'domain_events',
                'queue_map' => [],
                'processor_service_id' => 'enqueue.simple_bus.events_processor',
            ],
        ], 'events');
    }

    /**
     * @test
     */
    public function it_configures_commands_map()
    {
        $config = [
            'commands' => [
                'queue_map' => [
                    'FooClass' => 'foo',
                    'BooClass' => 'boo',
                ],
            ],
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'commands' => [
                'enabled' => true,
                'transport_name' => 'default',
                'default_queue' => 'asynchronous_commands',
                'queue_map' => [
                    'FooClass' => 'foo',
                    'BooClass' => 'boo',
                ],
                'processor_service_id' => 'enqueue.simple_bus.commands_processor',
            ],
        ], 'commands');
    }

    /**
     * @test
     */
    public function it_configures_events_map()
    {
        $config = [
            'events' => [
                'queue_map' => [
                    'FooClass' => 'foo',
                    'BooClass' => 'boo',
                ],
            ],
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'events' => [
                'enabled' => true,
                'transport_name' => 'default',
                'default_queue' => 'asynchronous_events',
                'queue_map' => [
                    'FooClass' => 'foo',
                    'BooClass' => 'boo',
                ],
                'processor_service_id' => 'enqueue.simple_bus.events_processor',
            ],
        ], 'events');
    }

    /**
     * @test
     */
    public function it_configures_commands_transport_and_processor()
    {
        $config = [
            'commands' => [
                'transport_name' => 'redis',
                'processor_service_id' => 'commands_processor',
            ],
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'commands' => [
                'enabled' => true,
                'transport_name' => 'redis',
                'default_queue' => 'asynchronous_commands',
                'queue_map' => [],
                'processor_service_id' => 'commands_processor',
            ],
        ], 'commands');
    }

    /**
     * @test
     */
    public function it_configures_events_transport_and_processor()
    {
        $config = [
            'events' => [
                'transport_name' => 'redis',
                'processor_service_id' => 'events_processor',
            ],
        ];

        $this->assertProcessedConfigurationEquals([$config], [
            'events' => [
                'enabled' => true,
                'transport_name' => 'redis',
                'default_queue' => 'asynchronous_events',
                'queue_map' => [],
                'processor_service_id' => 'events_processor',
            ],
        ], 'events');
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }
}
