<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('enqueue_simple_bus');
        $rootNode
            ->children()
            ->end()
        ;

        return $treeBuilder;
    }
}
