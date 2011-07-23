<?php

namespace Vich\GeographicalBundle\Templating\Helper;

use Vich\GeographicalBundle\Driver\AnnotationDriver;
use Vich\GeographicalBundle\Map\Map;
use Vich\GeographicalBundle\Map\MapMarker;
use Vich\GeographicalBundle\Map\MapProvider;
use Vich\GeographicalBundle\Map\Renderer\MapRendererInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * MapHelper.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class MapHelper extends Helper
{
    /**
     * @var Vich\GeographicalBundle\Driver\AnnotationDriver $driver
     */
    private $driver;
    
    /**
     * @var Vich\GeographicalBundle\Map\MapProvider $provider
     */
    private $provider;
    
    /**
     * @var Vich\GeographicalBundle\Map\MapRendererInterface $renderer
     */
    private $renderer;
    
    /**
     * Constructs a new instance of MapHelper.
     * 
     * @param Vich\GeographicalBundle\Driver\AnnotationDriver $driver The annotation driver
     * @param Vich\GeographicalBundle\Map\MapProvider $provider The provider
     * @param Vich\GeographicalBundle\Map\Renderer\MapRendererInterface Th renderer
     */
    public function __construct(AnnotationDriver $driver, MapProvider $provider, MapRendererInterface $renderer)
    {
        $this->driver = $driver;
        $this->provider = $provider;
        $this->renderer = $renderer;
    }
    
    /**
     * Gets the helper name.
     * 
     * @return string The name
     */
    public function getName()
    {
        return 'vichgeo';
    }
    
    /**
     * Renders javascripts used by the map renderer.
     * 
     * @return string The html output
     */
    public function renderJavascripts()
    {
        return $this->renderer->renderJavascripts();
    }
    
    /**
     * Renders stylesheets used by the map renderer.
     * 
     * @return string The html output
     */
    public function renderStylesheets()
    {
        return $this->renderer->renderStylesheets();
    }
    
    /**
     * Renders the the Map with the specified alias using the specified entities.
     * 
     * @param string $alias The Map alias
     * @param mixed $obj The object or array of objects to render
     * @return string The html output
     */
    public function prepareAndRender($alias, $obj)
    {
        $map = $this->provider->getMap($alias);
        
        if (!is_array($obj)) {
            $obj = array($obj);
        }
        
        foreach ($obj as $entity) {
            list($lat, $lng) = $this->getLatLng($entity);
            
            $marker = new MapMarker($lat, $lng);
            $infoWindow = $this->getInfoWindow($entity);
            if ($infoWindow)
            {
                //TODO: get UID from UnitOfWork
                $marker->setVarName('marker'.$entity->getId());
                $marker->setInfoWindow($infoWindow);
            }
            
            $map->addMarker($marker);
        }
        
        if (!$map->getCenter() && !$map->getAutoZoom() && count($obj) == 1) {
            $map->setCenter($lat, $lng);
        } else {
            $map->setAutoZoom(true);
        }
        
        return $this->renderer->render($map);
    }
    
    /**
     * Renders the Map.
     * 
     * @param string $alias The map alias
     * @return string The html output
     */
    public function render($alias)
    {
        $map = $this->provider->getMap($alias);
        
        return $this->renderer->render($map);
    }
    
    /**
     * Gets the latitude and longitude values of the object based on annotations.
     * 
     * @param type $obj The object
     * @return array An array
     */
    private function getLatLng($obj)
    {
        $annot = $this->driver->getGeographicalAnnotation($obj);
        if (!$annot) {
            throw new \InvalidArgumentException('Unable to find Geographical annotation');
        }
        
        $latMethod = sprintf('get%s', $annot->getLat());
        $lngMethod = sprintf('get%s', $annot->getLng());
        
        $lat = $obj->$latMethod();
        $lng = $obj->$lngMethod();
        
        return array($lat, $lng);
    }

    private function getInfoWindow($obj)
    {
        $annot = $this->driver->getGeographicalInfoWindowAnnotation($obj);
        if (!$annot) {
            //InfoWindow is optional
            return null;
        }

        $infoWindowMethod = $annot->getMethod();
        return $obj->$infoWindowMethod();

    }
}