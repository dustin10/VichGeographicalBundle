<?php

namespace Vich\GeographicalBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
/**
 * Configuration.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Gets the configuration tree builder for the extension.
     * 
     * @return Tree The configuration tree builder
     */
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();
        $root = $tb->root('vich_geographical');
        
        $root
            ->children()
                ->scalarNode('db_driver')->cannotBeOverwritten()->isRequired()->end()
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
                        ->scalarNode('icon_generator')
                            ->cannotBeEmpty()
                            ->defaultValue('Vich\\GeographicalBundle\\Map\\Generator\\DefaultMapMarkerIconGenerator')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('templating')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('engine')->defaultValue('twig')->end()
                        ->scalarNode('info_window')->defaultValue('VichGeographicalBundle:InfoWindow:default.html.twig')->end()
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
            ->end()
        ;
        
        return $tb;
    }
}