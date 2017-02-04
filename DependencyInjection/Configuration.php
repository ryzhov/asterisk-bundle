<?php

namespace Ryzhov\Bundle\AsteriskBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('asterisk');

        $this->addConnections($rootNode);
        $this->addClients($rootNode);

        return $treeBuilder;
    }

    protected function addClients(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('clients')
                    ->useAttributeAsKey('key')
                    ->canBeUnset()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('connection')->end()
                            ->scalarNode('logger_channel')->defaultValue(false)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    protected function addConnections(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('connections')
                    ->useAttributeAsKey('key')
                    ->canBeUnset()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('host')->defaultValue('localhost')->end()
                            ->scalarNode('port')->defaultValue(5038)->end()
                            ->scalarNode('username')->defaultValue('guest')->end()
                            ->scalarNode('secret')->defaultValue('guest')->end()
                            ->scalarNode('connect_timeout')->defaultValue(10)->end()
                            ->scalarNode('read_timeout')->defaultValue(10)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
