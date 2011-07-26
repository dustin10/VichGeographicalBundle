<?php

namespace Vich\GeographicalBundle\Map;

use Vich\GeographicalBundle\Map\MapCoordinate;
use Vich\GeographicalBundle\Map\MapMarker;
use Vich\GeographicalBundle\Map\Renderer\MapRendererInterface;
use Vich\GeographicalBundle\Map\Renderer\MapRenderer;

/**
 * Map.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class Map
{   
    const TYPE_ROADMAP = 'roadmap';
    const TYPE_SATELLITE = 'satellite';
    const TYPE_HYBRID = 'hybrid';
    
    /**
     * @var array $mapOptions
     */
    private $mapOptions = array(
        'zoom' => 10,
        'mapTypeId' => Map::TYPE_ROADMAP,
        'mapTypeControl' => false,
        'zoomControl' => false,
        'streetViewControl' => false
    );
    
    /**
     * @var Vich\GeographicalBundle\Map\MapCoordinate $center
     */
    private $center = null;
    
    /**
     * @var array $markers
     */
    private $markers = array();
    
    /**
     *
     * @var boolean $autoZoom
     */
    private $autoZoom = false;
    
    /**
     * @var string $varName
     */
    private $varName;
    
    /**
     * @var integer $containerId
     */
    private $containerId;
    
    /**
     * @var integer $width
     */
    private $width;
    
    /**
     * @var integer $height
     */
    private $height;
    
    /**
     * @var boolean $showInfoWindowsForMarkers
     */
    private $showInfoWindowsForMarkers = false;
    
    /**
     * Gets the map type.
     * 
     * @return string The map type
     */
    public function getMapType()
    {
       return $this->mapOptions['mapTypeId'];
    }
    
    /**
     * Sets the map type.
     * 
     * @param type $value The map type
     */
    public function setMapType($value)
    {
        $this->mapOptions['mapTypeId'] = $value;
    }
    
    /**
     * Determines if the map will display the map type control.
     * 
     * @return boolean True if should display map type control, false otherwise
     */
    public function getShowMapTypeControl()
    {
       return $this->mapOptions['mapTypeControl'];
    }
    
    /**
     * Sets whether or not to show the map type control.
     * 
     * @param type $value True if should display map type control, false otherwise
     */
    public function setShowMapTypeControl($value)
    {
        $this->mapOptions['mapTypeControl'] = $value;
    }
    
    /**
     * Gets the zoom level.
     * 
     * @return integer The zoom level
     */
    public function getZoom()
    {
        return $this->mapOptions['zoom'];
    }
    
    /**
     * Sets the zoom level.
     * 
     * @param integer $value The zoom
     */
    public function setZoom($value)
    {
        $this->mapOptions['zoom'] = $value;
    }
    
    /**
     * Determines if the map will display the zoom control.
     * 
     * @return boolean True if should display zoom control, false otherwise
     */
    public function getShowZoomControl()
    {
        return $this->mapOptions['zoomControl'];
    }
    
    /**
     * Sets whether or not to show the zoom control.
     * 
     * @param type $value True if should display zoom control, false otherwise
     */
    public function setShowZoomControl($value)
    {
        $this->mapOptions['zoomControl'] = $value;
    }
    
    /**
     * Determines if the map will display the street view control.
     * 
     * @return boolean True if should display street view control, false otherwise
     */
    public function getShowStreetViewControl()
    {
        return $this->mapOptions['streetViewControl'];
    }
    
    /**
     * Sets whether or not to show the street view control.
     * 
     * @param type $value True if should display street view control, false otherwise
     */
    public function setShowStreetViewControl($value)
    {
        $this->mapOptions['streetViewControl'] = $value;
    }
    
    /**
     * Gets the center coordinate of the map.
     * 
     * @return Vich\GeographicalBundle\Map\MapCoordinate The center coordinate
     */
    public function getCenter()
    {
        return $this->center;
    }
    
    /**
     * Sets the center coordinate of the map.
     * 
     * @param double $lat The latitude
     * @param double $lng The longitude
     */
    public function setCenter($lat, $lng)
    {
        $this->center = new MapCoordinate($lat, $lng);
    }
    
    /**
     * Determines if the map will zoom in or out to fit all markers in the visible
     * bounds of the map.
     * 
     * @return boolean True if map will auto zoom, false otherwise
     */
    public function getAutoZoom()
    {
        return $this->autoZoom;
    }
    
    /**
     * Sets whether or not the map should zoom in or out to fit all markers in 
     * the visible bounds of the map.
     * @param boolean $value True if map should auto zoom, false otherwise
     */
    public function setAutoZoom($value)
    {
        $this->autoZoom = $value;
    }
    
    /**
     * Gets the javascript variable name.
     * 
     * @return string The javascript variable name
     */
    public function getVarName()
    {
        return $this->varName;
    }
    
    /**
     * Sets the javascript variable name.
     * 
     * @param type $value The javascript variable name
     */
    public function setVarName($value)
    {
        $this->varName = $value;
    }
    
    /**
     * Gets the markers for the map.
     * 
     * @return array An array
     */
    public function getMarkers()
    {
        return $this->markers;
    }
    
    /**
     * Adds a marker to the map.
     * 
     * @param type $lat The longitude
     * @param type $lng The latitude
     */
    public function addMarker(MapMarker $marker)
    {
        $this->markers[] = $marker;
    }
    
    /**
     * Gets the map options.
     * 
     * @return array An array
     */
    public function getMapOptions()
    {
        return $this->mapOptions;
    }
    
    /**
     * Gets the container div id.
     * 
     * @return type The container id
     */
    public function getContainerId()
    {
        return $this->containerId;
    }
    
    /**
     * Sets the container div id.
     * 
     * @param string $value The container id
     */
    public function setContainerId($value)
    {
        $this->containerId = $value;
    }
    
    /**
     * Gets the width.
     * 
     * @return integer The width
     */
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * Sets the width.
     * 
     * @param integer $value The width
     */
    public function setWidth($value)
    {
        $this->width = $value;
    }
    
    /**
     * Gets the height.
     * 
     * @return integer The height
     */
    public function getHeight()
    {
        return $this->height;
    }
    
    /**
     * Sets the height.
     * 
     * @param integer $value The height
     */
    public function setHeight($value)
    {
        $this->height = $value;
    }
    
    /**
     * Gets whether or not the map should show info windows for the markers.
     * 
     * @return boolean True if show info windows, false otherwise
     */
    public function getShowInfoWindowsForMarkers()
    {
        return $this->showInfoWindowsForMarkers;
    }
    
    /**
     * Sets whether or not the map should show info windows for the markers.
     * 
     * @param type $value True if show info windows, false otherwise
     */
    public function setShowInfoWindowsForMarkers($value)
    {
        $this->showInfoWindowsForMarkers = $value;
    }
    
    /**
     * Constructs a new instance of Map.
     * 
     * @param string $containerId The id of the container div
     * @param integer $width The width of the map
     * @param integer $height The height of the map
     * @param string $varName The javascript variable name
     */
    public function __construct($containerId = 'map_canvas', $width = 300, $height = 300, $varName = 'map')
    {
        $this->containerId = $containerId;
        $this->width = $width;
        $this->height = $height;
        $this->varName = $varName;
        if ($this->varName === 'map') {
            $this->varName = sprintf('map%s', uniqid());
        }
    }
}
