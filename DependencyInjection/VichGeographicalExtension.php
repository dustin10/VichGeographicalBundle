<?php

namespace Vich\GeographicalBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Vich\GeographicalBundle\DependencyInjection\Configuration;

/**
 * VichGeographicalExtension.
 *
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class VichGeographicalExtension extends Extension
{   
    /**
     * @var array $adapterMap
     */
    private $adapterMap = array(
        'orm' => 'Vich\GeographicalBundle\Adapter\ORM\DoctrineORMAdapter',
        'mongodb' => 'Vich\GeographicalBundle\Adapter\ODM\MongoDB\MongoDBAdapter'
    );
    
    /**
     * @var array $tagMap
     */
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
        $configuration = new Configuration();
        
        $config = $this->processConfiguration($configuration, $configs);
        
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        $toLoad = array('query.xml', 'map.xml', 'listener.xml', 'twig.xml', 'adapter.xml', 'templating.xml');
        foreach ($toLoad as $file) {
            $loader->load($file);
        }
        
        $dbDriver = strtolower($config['db_driver']);
        if (!in_array($dbDriver, array_keys($this->tagMap))) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid "db_driver" configuration option specified: "%s"',
                    $config['db_driver']
                )
            );
        }
        
        $container->setParameter('vich_geographical.adapter.class', $this->adapterMap[$dbDriver]);
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

        if (null !== $config['google']['only_one_info_window']) {
            $rendererOptions['google_only_one_info_window'] = $config['google']['only_one_info_window'];
        }
        
        $container->setAlias('vich_geographical.query_service', $config['query_service']);
        $container->setAlias('vich_geographical.map_renderer', $config['map_renderer']);
        $container->setAlias('vich_geographical.icon_generator', $config['icon_generator']);
        
        $container->setParameter('vich_geographical.map_renderer.options', $rendererOptions);
    }
}