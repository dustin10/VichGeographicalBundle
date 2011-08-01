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
     * @var array $driverMap
     */
    private $driverMap = array(
        'orm'     => 'Vich\GeographicalBundle\Listener\ORM\GeographicalListener',
        'mongodb' => 'Vich\GeographicalBundle\Listener\ODM\MongoDB\GeographicalListener'
    );
    
    private $tagMap = array(
        'orm' => 'doctrine.event_subscriber',
        'mongodb' => 'doctrine.common.event_subscriber'
    );
    
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
        
        $toLoad = array('query.xml', 'map.xml', 'listener.xml', 'twig.xml');
        foreach ($toLoad as $file) {
            $loader->load($file);
        }
        
        $dbDriver = strtolower($config['db_driver']);
        if (!in_array($dbDriver, array_keys($this->driverMap))) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid "db_driver" configuration option specified: "%s"',
                    $config['db_driver']
                )
            );
        }
        
        $container->setParameter('vich_geographical.listener.geographical.class', $this->driverMap[$dbDriver]);
        $container->getDefinition('vich_geographical.listener.geographical')->addTag($this->tagMap[$dbDriver]);
        
        $templateEngine = strtolower($config['templating']['engine']);
        if (!in_array($templateEngine, array('twig', 'php'))) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The "templating.engine" configuration option specified: "%s"',
                    $templateEngine
                )
            );
        }
        
        $loader->load(sprintf('templating_%s.xml', $templateEngine));
        
        $container->setParameter('vich_geographical.info_window.template_name', $config['templating']['info_window']);

        $rendererOptions = array();
        if (null !== $config['leaflet']['api_key']) {
            $rendererOptions['leaflet_api_key'] = $config['leaflet']['api_key'];
        }
        
        if (null !== $config['bing']['api_key']) {
            $rendererOptions['bing_api_key'] = $config['bing']['api_key'];
        }
        
        $container->setParameter('vich_geographical.query_service.class', $config['class']['query_service']);
        $container->setParameter('vich_geographical.map_renderer.options', $rendererOptions);
        $container->setParameter('vich_geographical.map_renderer.class', $config['class']['map_renderer']);
    }
}