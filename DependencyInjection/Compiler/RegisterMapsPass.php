<?php

namespace Vich\GeographicalBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * RegisterMapsPass.
 * 
 * @author Dustin Dobervic <ddobervich@gmail.com>
 */
class RegisterMapsPass implements CompilerPassInterface
{
    /**
     * Collect all of the services tagged as vichgeo.map.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('vich_geographical.map_provider')) {
            return;
        }
        
        $providerDefinition = $container->getDefinition('vich_geographical.map_provider');
        
        $services = $container->findTaggedServiceIds('vichgeo.map');
        foreach ($services as $id => $attributes) {
            if (isset($attributes[0]['alias'])) {
                $providerDefinition->addMethodCall(
                    'addMapServiceId',
                    array($id, $attributes[0]['alias'])
                );
            }
        }
    }
}