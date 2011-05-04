<?php

namespace Vich\GeographicalBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class Configuration
{
    /**
     * Gets the configuration tree for the extension.
     * 
     * @return Tree The configuration tree
     */
    public function getConfigTree()
    {
        $tb = new TreeBuilder();
        $root = $tb->root('vich_geographical');
        
        $root
            ->children()
                ->arrayNode('orm')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->performNoDeepMerging()
                        ->children()
                            ->scalarNode('query_service')->defaultValue('google')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('mongodb')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->performNoDeepMerging()
                        ->children()
                            ->scalarNode('query_service')->defaultValue('google')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('geographical')
                            ->cannotBeEmpty()
                            ->defaultValue('Vich\\GeographicalBundle\\Listener\\GeographicalListener')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        
        return $tb->buildTree();
    }
}