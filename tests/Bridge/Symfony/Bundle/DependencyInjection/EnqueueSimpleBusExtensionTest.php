<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Tests\Bridge\Symfony\Bundle\DependencyInjection;

use Enqueue\SimpleBus\Bridge\Symfony\Bundle\DependencyInjection\EnqueueSimpleBusExtension;
use Enqueue\SimpleBus\Bridge\Symfony\Serializer\EnvelopeNormalizer;
use Enqueue\SimpleBus\Bridge\Symfony\Serializer\ObjectSerializer as SymfonyObjectSerializer;
use Enqueue\SimpleBus\Consumption\Extension\LongRunningExtension;
use Enqueue\SimpleBus\Routing\FixedQueueNameResolver;
use Enqueue\SimpleBus\Routing\MappedQueueNameResolver;
use Enqueue\SimpleBus\SimpleBusProcessor;
use Enqueue\SimpleBus\SimpleBusPublisher;
use LogicException;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use SimpleBus\Serialization\Envelope\Serializer\MessageInEnvelopeSerializer;
use SimpleBus\Serialization\ObjectSerializer;
use Symfony\Component\DependencyInjection\Reference;

class EnqueueSimpleBusExtensionTest extends AbstractExtensionTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->container->setParameter('kernel.bundles', [
            'SimpleBusAsynchronousBundle' => true,
        ]);
    }

    /**
     * @test
     */
    public function it_requires_simple_bus_asynchronous_bundle()
    {
        $this->container->setParameter('kernel.bundles', []);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('You need to enable "SimpleBusAsynchronousBundle".');

        $this->load();
    }

    /**
     * @test
     */
    public function it_registers_serialization_services()
    {
        $this->load();

        $this->assertContainerBuilderHasService(ObjectSerializer::class, SymfonyObjectSerializer::class);
        $this->assertContainerBuilderHasAlias(MessageInEnvelopeSerializer::class, 'simple_bus.asynchronous.message_serializer');
        $this->assertContainerBuilderHasService(EnvelopeNormalizer::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(EnvelopeNormalizer::class, 'serializer.normalizer');
    }

    /**
     * @test
     */
    public function it_registers_publisher_for_commands()
    {
        $this->load([
            'commands' => null,
        ]);

        $this->assertContainerBuilderHasService('enqueue.simple_bus.commands_publisher', SimpleBusPublisher::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.commands_publisher', 0, new Reference(MessageInEnvelopeSerializer::class));
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.commands_publisher', 1, new Reference('enqueue.simple_bus.commands_queue_resolver'));
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.commands_publisher', 2, new Reference('enqueue.transport.default.context'));
    }

    /**
     * @test
     */
    public function it_registers_publisher_for_events()
    {
        $this->load([
            'events' => null,
        ]);

        $this->assertContainerBuilderHasService('enqueue.simple_bus.events_publisher', SimpleBusPublisher::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.events_publisher', 0, new Reference(MessageInEnvelopeSerializer::class));
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.events_publisher', 1, new Reference('enqueue.simple_bus.events_queue_resolver'));
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.events_publisher', 2, new Reference('enqueue.transport.default.context'));
    }

    /**
     * @test
     */
    public function it_registers_processor_for_commands()
    {
        $this->load([
            'commands' => null,
        ]);

        $this->assertContainerBuilderHasService('enqueue.simple_bus.commands_processor', SimpleBusProcessor::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'enqueue.simple_bus.commands_processor',
            0,
            new Reference('simple_bus.asynchronous.standard_serialized_command_envelope_consumer')
        );
    }

    /**
     * @test
     */
    public function it_registers_processor_for_events()
    {
        $this->load([
            'events' => null,
        ]);

        $this->assertContainerBuilderHasService('enqueue.simple_bus.events_processor', SimpleBusProcessor::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'enqueue.simple_bus.events_processor',
            0,
            new Reference('simple_bus.asynchronous.standard_serialized_event_envelope_consumer')
        );
    }

    /**
     * @test
     */
    public function it_registers_fixed_queue_name_resolver_for_commands()
    {
        $this->load(['commands' => 'async_commands']);

        $this->assertContainerBuilderHasService('enqueue.simple_bus.commands_queue_resolver', FixedQueueNameResolver::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.commands_queue_resolver', 0, 'async_commands');
    }

    /**
     * @test
     */
    public function it_registers_fixed_queue_name_resolver_for_events()
    {
        $this->load(['events' => 'domain_events']);

        $this->assertContainerBuilderHasService('enqueue.simple_bus.events_queue_resolver', FixedQueueNameResolver::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.events_queue_resolver', 0, 'domain_events');
    }

    /**
     * @test
     */
    public function it_registers_mapped_queue_name_resolver_for_commands()
    {
        $this->load(['commands' => [
            'default_queue' => 'async_commands',
            'queue_map' => [
                'FooClass' => 'foo',
                'BarClass' => 'bar',
            ],
        ]]);

        $this->assertContainerBuilderHasService('enqueue.simple_bus.commands_queue_resolver', MappedQueueNameResolver::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.commands_queue_resolver', 0, [
            'FooClass' => 'foo',
            'BarClass' => 'bar',
        ]);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.commands_queue_resolver', 1, 'async_commands');
    }

    /**
     * @test
     */
    public function it_registers_mapped_queue_name_resolver_for_events()
    {
        $this->load(['events' => [
            'default_queue' => 'domain_events',
            'queue_map' => [
                'FooClass' => 'foo',
                'BarClass' => 'bar',
            ],
        ]]);

        $this->assertContainerBuilderHasService('enqueue.simple_bus.events_queue_resolver', MappedQueueNameResolver::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.events_queue_resolver', 0, [
            'FooClass' => 'foo',
            'BarClass' => 'bar',
        ]);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('enqueue.simple_bus.events_queue_resolver', 1, 'domain_events');
    }

    /**
     * @test
     */
    public function it_registers_long_running_extension()
    {
        $this->container->setParameter('kernel.bundles', [
            'SimpleBusAsynchronousBundle' => true,
            'LongRunningBundle' => true,
        ]);

        $this->load();

        $this->assertContainerBuilderHasService(LongRunningExtension::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag(LongRunningExtension::class, 'enqueue.consumption.extension', ['priority' => -999]);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            LongRunningExtension::class,
            0,
            new Reference('long_running.delegating_cleaner')
        );
    }

    /**
     * @test
     */
    public function it_requires_simple_bus_command_bus_bundle()
    {
        $this->container->prependExtensionConfig('enqueue_simple_bus', ['commands' => null]);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('You need to enable "SimpleBusCommandBusBundle".');

        $this->load();
    }

    /**
     * @test
     */
    public function it_configures_simple_bus_command_services()
    {
        $this->container->prependExtensionConfig('enqueue_simple_bus', ['commands' => null]);

        $this->container->setParameter('kernel.bundles', [
            'SimpleBusAsynchronousBundle' => true,
            'SimpleBusCommandBusBundle' => true,
        ]);

        $this->load();

        $config = [
            'commands' => [
                'publisher_service_id' => 'enqueue.simple_bus.commands_publisher',
            ],
        ];

        $this->assertEquals($config, $this->container->getExtensionConfig('simple_bus_asynchronous')[0]);
    }

    /**
     * @test
     */
    public function it_requires_simple_bus_event_bus_bundle()
    {
        $this->container->prependExtensionConfig('enqueue_simple_bus', ['events' => null]);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('You need to enable "SimpleBusEventBusBundle".');

        $this->load();
    }

    /**
     * @test
     */
    public function it_configures_simple_bus_event_services()
    {
        $this->container->prependExtensionConfig('enqueue_simple_bus', ['events' => null]);

        $this->container->setParameter('kernel.bundles', [
            'SimpleBusAsynchronousBundle' => true,
            'SimpleBusEventBusBundle' => true,
        ]);

        $this->load();

        $config = [
            'events' => [
                'publisher_service_id' => 'enqueue.simple_bus.events_publisher',
                'strategy' => 'predefined',
            ],
        ];

        $this->assertEquals($config, $this->container->getExtensionConfig('simple_bus_asynchronous')[0]);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new EnqueueSimpleBusExtension(),
        ];
    }
}
