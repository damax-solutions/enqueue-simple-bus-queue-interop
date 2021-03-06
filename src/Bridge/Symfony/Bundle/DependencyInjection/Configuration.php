<?php

declare(strict_types=1);

namespace Enqueue\SimpleBus\Bridge\Symfony\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const TYPE_COMMANDS = 'commands';
    public const TYPE_EVENTS = 'events';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('enqueue_simple_bus');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->append($this->messagesNode(self::TYPE_COMMANDS))
                ->append($this->messagesNode(self::TYPE_EVENTS))
            ->end()
        ;

        return $treeBuilder;
    }

    private function messagesNode(string $type): ArrayNodeDefinition
    {
        return (new ArrayNodeDefinition($type))
            ->beforeNormalization()
                ->ifString()
                ->then(function (string $config): array {
                    return ['default_queue' => $config, 'enabled' => true];
                })
            ->end()
            ->canBeEnabled()
            ->children()
                ->scalarNode('transport_name')
                    ->cannotBeEmpty()
                    ->defaultValue('default')
                ->end()
                ->scalarNode('default_queue')
                    ->cannotBeEmpty()
                    ->defaultValue(sprintf('asynchronous_%s', $type))
                ->end()
                ->scalarNode('processor_service_id')
                    ->cannotBeEmpty()
                    ->defaultValue(sprintf('enqueue.simple_bus.%s_processor', $type))
                ->end()
                ->arrayNode('queue_map')
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey(true)
                    ->scalarPrototype()
                        ->isRequired()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
