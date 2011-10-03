<?php

namespace Vich\GeographicalBundle\Map\Provider;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MapProvider.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class MapProvider
{
    /**
     * @var ContainerInterface $container
     */
    private $container;
    
    /**
     * @var array $mapServiceIds
     */
    private $mapServiceIds = array();
    
    /**
     * @var array $map
     */
    private $maps = array();
    
    /**
     * Constructs a new instance of ContainerInterface.
     * 
     * @param ContainerInterface $container The container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * Adds a Map.
     * 
     * @param Map $map The map
     * @param string $alias The map alias
     */
    public function addMapServiceId($id, $alias)
    {
        $this->mapServiceIds[$alias] = $id;
    }
    
    /**
     * Gets a Map.
     * 
     * @param Map $alias A Map alias
     */
    public function getMap($alias)
    {
        if (!array_key_exists($alias, $this->maps)) {
            $this->loadMap($alias);
        }
        
        return $this->maps[$alias];
    }
    
    /**
     * Loads a Map from the container.
     * 
     * @param string $alias The Map alias
     */
    private function loadMap($alias)
    {
        $this->maps[$alias] = $this->container->get($this->mapServiceIds[$alias]);
    }
}
