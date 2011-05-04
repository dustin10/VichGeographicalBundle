<?php

namespace Vich\GeographicalBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Vich\GeographicalBundle\DependencyInjection\Configuration;

/**
 * VichGeographicalExtension.
 *
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class VichGeographicalExtension extends Extension
{
    /**
     * Loads the extension.
     * 
     * @param array $configs The configuration
     * @param ContainerBuilder $container The container builder
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        
        $config = $processor->process($configuration->getConfigTree(), $configs);
        
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('annotations.xml');
        $loader->load('services.xml');
        $loader->load('listeners.xml');
        
        
    }
}