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
                ->arrayNode('twig')
                    ->performNoDeepMerging()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enabled')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('query_service')
                            ->cannotBeEmpty()
                            ->defaultValue('Vich\\GeographicalBundle\\QueryService\\GoogleQueryService')
                        ->end()
                        ->scalarNode('map_renderer')
                            ->cannotBeEmpty()
                            ->defaultValue('Vich\\GeographicalBundle\\Map\\Renderer\\GoogleMapRenderer')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('leaflet')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('api_key')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('bing')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('api_key')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('templating')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('engine')->defaultValue('twig')->end()
                        ->scalarNode('info_window')->defaultValue('VichGeographicalBundle:InfoWindow:default.html.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;
        
        return $tb->buildTree();
    }
}