<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Bridge\Symfony\Bundle\DependencyInjection;

use Enqueue\SimpleBus\Consumption\Extension\LongRunningExtension;
use Enqueue\SimpleBus\Routing\FixedQueueNameResolver;
use Enqueue\SimpleBus\Routing\MappedQueueNameResolver;
use Enqueue\SimpleBus\SimpleBusProcessor;
use Enqueue\SimpleBus\SimpleBusPublisher;
use LogicException;
use SimpleBus\Serialization\Envelope\Serializer\MessageInEnvelopeSerializer;
use SimpleBus\Serialization\ObjectSerializer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class EnqueueSimpleBusExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        $this->requireBundle('SimpleBusAsynchronousBundle', $container);

        $config = $container->getExtensionConfig($this->getAlias());
        $merged = $this->processConfiguration($this->getConfiguration($config, $container), $config);

        // Common for all messages.
        $container->prependExtensionConfig('simple_bus_asynchronous', [
            'object_serializer_service_id' => ObjectSerializer::class,
        ]);

        // Enable async commands.
        if ($merged['commands']['enabled']) {
            $this->requireBundle('SimpleBusCommandBusBundle', $container);

            $container->prependExtensionConfig('simple_bus_asynchronous', [
                'commands' => [
                    'publisher_service_id' => 'enqueue.simple_bus.commands_publisher',
                ],
            ]);
        }

        // Enable async events.
        if ($merged['events']['enabled']) {
            $this->requireBundle('SimpleBusEventBusBundle', $container);

            $container->prependExtensionConfig('simple_bus_asynchronous', [
                'events' => [
                    'publisher_service_id' => 'enqueue.simple_bus.events_publisher',
                    'strategy' => 'predefined',
                ],
            ]);
        }
    }

    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        if ($config['commands']['enabled']) {
            $this->configureQueue(Configuration::TYPE_COMMANDS, $config['commands'], $container);
        }

        if ($config['events']['enabled']) {
            $this->configureQueue(Configuration::TYPE_EVENTS, $config['events'], $container);
        }

        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['LongRunningBundle'])) {
            $container
                ->register(LongRunningExtension::class)
                ->addArgument(new Reference('long_running.delegating_cleaner'))
                ->addTag('enqueue.consumption.extension', ['priority' => -999])
            ;
        }
    }

    private function configureQueue(string $type, array $config, ContainerBuilder $container): self
    {
        $queueResolverId = sprintf('enqueue.simple_bus.%s_queue_resolver', $type);
        $publisherId = sprintf('enqueue.simple_bus.%s_publisher', $type);
        $transportId = sprintf('enqueue.transport.%s.context', $config['transport_name']);
        $consumerId = sprintf('simple_bus.asynchronous.standard_serialized_%s_envelope_consumer', rtrim($type, 's'));

        // Register publisher.
        $container
            ->register($publisherId, SimpleBusPublisher::class)
            ->addArgument(new Reference(MessageInEnvelopeSerializer::class))
            ->addArgument(new Reference($queueResolverId))
            ->addArgument(new Reference($transportId))
        ;

        // Register processor.
        $container
            ->register($config['processor_service_id'], SimpleBusProcessor::class)
            ->addArgument(new Reference($consumerId))
            ->setPublic(true)
        ;

        if (count($config['queue_map'])) {
            $container
                ->register($queueResolverId, MappedQueueNameResolver::class)
                ->addArgument($config['queue_map'])
                ->addArgument($config['default_queue'])
            ;
        } else {
            $container
                ->register($queueResolverId, FixedQueueNameResolver::class)
                ->addArgument($config['default_queue'])
            ;
        }

        return $this;
    }

    /**
     * @throws LogicException
     */
    private function requireBundle(string $bundleName, ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (!isset($bundles[$bundleName])) {
            throw new LogicException(sprintf('You need to enable "%s".', $bundleName));
        }
    }
}
