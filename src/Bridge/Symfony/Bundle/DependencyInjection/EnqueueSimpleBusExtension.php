<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Bridge\Symfony\Bundle\DependencyInjection;

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
    }

    /**
     * @throws LogicException
     */
    private function requireBundle($bundleName, ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (!isset($bundles[$bundleName])) {
            throw new LogicException(sprintf('You need to enable "%s" as well', $bundleName));
        }
    }
}
