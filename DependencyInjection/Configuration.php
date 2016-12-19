<?php

namespace A5sys\MonitorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use A5sys\MonitorBundle\Converter\DataTypeConverter;

/**
 *
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('monitor');
        $rootNode
            ->children()
                ->booleanNode('enable')->defaultTrue()->end()
                ->arrayNode('slow_threshold')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('warning')->defaultValue('1000')->end()
                        ->integerNode('error')->defaultValue('3000')->end()
                    ->end()
                ->end()
            ->end();

        //for each type add a configuration
        $types = DataTypeConverter::getAllTypes();

        $typeNode = $rootNode
                    ->children()
                        ->arrayNode('types')
                            ->addDefaultsIfNotSet()
                                ->children();
        foreach ($types as $type) {
            $typeNode->booleanNode($type)->defaultTrue();
        }

        $typeNode->end()->end()->end()->end();

        return $treeBuilder;
    }
}
