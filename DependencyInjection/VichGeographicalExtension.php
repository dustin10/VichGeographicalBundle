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
     * @var array $entityManagers
     */
    private $entityManagers = array();
    
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
        $loader->load('query.xml');
        $loader->load('listener.xml');
        
        $container->setParameter('vich_geographical.query_service.class', $config['class']['query_service']);
        $container->setParameter('vich_geographical.listener.geographical.class', $config['class']['geographical_listener']);
        
        $listenerName = 'vich_geographical.listener.geographical';
        foreach ($config['orm'] as $name => $params) {
            if ($params['enabled']) {
                $definition = $container->getDefinition($listenerName);
                $definition->addTag('doctrine.event_subscriber', array('connection' => $name));
            }
            
            $this->entityManagers[] = $name;
        }
    }
    
    /**
     * Validates the DBAL configuration.
     * 
     * @param ContainerBuilder $container The container builder
     */
    public function validateConfiguration(ContainerBuilder $container)
    {
        foreach ($this->entityManagers as $name) {
            if (!$container->hasDefinition(sprintf('doctrine.dbal.%s_connection', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: DBAL connection "%s" not found', $this->getAlias(), $name));
            }
        }
    }
}