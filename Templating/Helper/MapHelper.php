<?php

namespace Vich\GeographicalBundle\Templating\Helper;

use Vich\GeographicalBundle\Driver\AnnotationDriver;
use Vich\GeographicalBundle\Map\Map;
use Vich\GeographicalBundle\Map\Marker\MapMarker;
use Vich\GeographicalBundle\Map\Provider\MapProvider;
use Vich\GeographicalBundle\Map\Renderer\MapRendererInterface;
use Vich\GeographicalBundle\Map\Marker\InfoWindow\InfoWindowRendererInterface;
use Vich\GeographicalBundle\Map\Marker\Icon\IconGeneratorInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * MapHelper.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class MapHelper extends Helper
{
    /**
     * @var AnnotationDriver $driver
     */
    private $driver;
    
    /**
     * @var MapProvider $provider
     */
    private $provider;
    
    /**
     * @var MapRendererInterface $renderer
     */
    private $renderer;
    
    /**
     * @var InfoWindowBuilderInterface $infoWindowBuilder
     */
    private $infoWindowBuilder;
    
    /**
     * @var IconGeneratorInterface $iconGenerator
     */
    private $iconGenerator;
    
    /**
     * Constructs a new instance of MapHelper.
     * 
     * @param AnnotationDriver $driver The annotation driver
     * @param MapProvider $provider The provider
     * @param MapRendererInterface Th renderer
     * @param InfoWindowBuilderInterface The info window renderer
     * @param IconGeneratorInterface $iconGenerator The marker icon url generator
     */
    public function __construct(AnnotationDriver $driver, MapProvider $provider, 
        MapRendererInterface $renderer, InfoWindowRendererInterface $infoWindowBuilder,
        IconGeneratorInterface $iconGenerator)
    {
        $this->driver = $driver;
        $this->provider = $provider;
        $this->renderer = $renderer;
        $this->infoWindowBuilder = $infoWindowBuilder;
        $this->iconGenerator = $iconGenerator;
    }
    
    /**
     * Gets the helper name.
     * 
     * @return string The name
     */
    public function getName()
    {
        return 'vich_geographical';
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
        if (!is_array($obj) && !($obj instanceof \IteratorAggregate)) {
            $obj = array($obj);
        }
        
        foreach ($obj as $entity) {
            list($lat, $lng) = $this->getLatLng($entity);
            
            if (($lat == 0 && $lng == 0) || is_null($lat) || is_null($lng)) {
                continue;
            }
            
            $marker = new MapMarker($lat, $lng);
            
            if ($map->getShowInfoWindowsForMarkers()) {
                $infoWindow = $this->infoWindowBuilder->build($entity);
                $marker->setInfoWindow($infoWindow);
            }
            
            $iconUrl = $this->iconGenerator->generateIcon($entity);
            if (null !== $iconUrl) {
                $marker->setIcon($iconUrl);
            }
            
            $map->addMarker($marker);
        }
        
        if (!$map->getAutoZoom() && count($obj) == 1) {
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
    protected function getLatLng($obj)
    {
        $annot = $this->driver->readGeoAnnotation($obj);
        if (null === $annot) {
            throw new \InvalidArgumentException('Unable to find Geographical annotation.');
        }
        
        $latMethod = sprintf('get%s', $annot->getLat());
        $lngMethod = sprintf('get%s', $annot->getLng());
        
        $lat = $obj->$latMethod();
        $lng = $obj->$lngMethod();
        
        return array($lat, $lng);
    }
}