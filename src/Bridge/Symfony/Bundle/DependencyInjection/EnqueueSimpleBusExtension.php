<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Bridge\Symfony\Bundle\DependencyInjection;

use Enqueue\SimpleBus\Routing\FixedQueueNameResolver;
use Enqueue\SimpleBus\Routing\MappedQueueNameResolver;
use LogicException;
use SimpleBus\Serialization\ObjectSerializer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class EnqueueSimpleBusExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        $this->requireBundle('SimpleBusAsynchronousBundle', $container);

        $container->prependExtensionConfig('simple_bus_asynchronous', [
            'object_serializer_service_id' => ObjectSerializer::class,
        ]);
    }

    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $this->configureQueueResolver(Configuration::TYPE_EVENTS, $config['events'], $container);
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

    private function configureQueueResolver(string $type, array $config, ContainerBuilder $container): self
    {
        $queueResolver = sprintf('enqueue.simple_bus.%s_queue_resolver', $type);

        if (count($config['queue_map'])) {
            $container
                ->register($queueResolver, MappedQueueNameResolver::class)
                ->addArgument($config['queue_map'])
                ->addArgument($config['default_queue'])
            ;
        } else {
            $container
                ->register($queueResolver, FixedQueueNameResolver::class)
                ->addArgument($config['default_queue'])
            ;
        }

        return $this;
    }
}
