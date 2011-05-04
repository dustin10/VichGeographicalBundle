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
                            ->scalarNode('enabled')->defaultTrue()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('geographical_listener')
                            ->cannotBeEmpty()
                            ->defaultValue('Vich\\GeographicalBundle\\Listener\\GeographicalListener')
                        ->end()
                        ->scalarNode('query_service')
                            ->cannotBeEmpty()
                            ->defaultValue('Vich\\GeographicalBundle\\QueryService\\GoogleQueryService')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        
        return $tb->buildTree();
    }
}