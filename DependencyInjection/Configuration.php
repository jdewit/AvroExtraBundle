<?php

namespace Avro\ExtraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('avro_extra');

        $rootNode
            ->children()
                ->scalarNode('db_driver')->defaultValue('mongodb')->end()
                ->booleanNode('twig')->defaultFalse()->cannotBeEmpty()->end()
                ->booleanNode('menu')->defaultFalse()->cannotBeEmpty()->end()
                ->booleanNode('form')->defaultFalse()->cannotBeEmpty()->end()
                ->booleanNode('ajax')->defaultFalse()->cannotBeEmpty()->end()
                ->booleanNode('exception')->defaultFalse()->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
