<?php

namespace Vich\GeographicalBundle\Map;

use Vich\GeographicalBundle\Map\MapCoordinate;
use Vich\GeographicalBundle\Map\MapMarkerInfoWindow;

/**
 * MapMarker.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class MapMarker
{    
    /**
     * @var string $varName
     */
    private $varName;
    
    /**
     * @var Vich\GeographicalBundle\Map\MapCoordinate $coordinate
     */
    private $coordinate;
    
    /**
     * @var string $icon
     */
    private $icon;
    
    /**
     * @var Vich\GeographicalBundle\Map\MapMarkerInfoWindow $infoWindow
     */
    private $infoWindow;
    
    /**
     * Gets the javascript variable name.
     * 
     * @return type The javascript variable name
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
     * Gets the coordinate.
     * 
     * @return Vich\GeographicalBundle\Map\MapCoordinate The coordinate
     */
    public function getCoordinate()
    {
        return $this->coordinate;
    }
    
    /**
     * Sets the coordinate.
     * 
     * @param Vich\GeographicalBundle\Map\MapCoordinate $value The coordinate
     */
    public function setCoordinate(MapCoordinate $value)
    {
        $this->coordinate = $value;
    }
    
    /**
     * Gets the icon url.
     * 
     * @return string The icon url
     */
    public function getIcon()
    {
        return $this->icon;
    }
    
    /**
     * Sets the icon url.
     * 
     * @param string $value The icon url 
     */
    public function setIcon($value)
    {
        $this->icon = $value;
    }
    
    /**
     * Gets the map marker's info window.
     * 
     * @return Vich\GeographicalBundle\Map\MapMarkerInfoWindow The info window
     */
    public function getInfoWindow()
    {
        return $this->infoWindow;
    }
    
    /**
     * Sets the map marker's info window.
     * 
     * @param Vich\GeographicalBundle\Map\MapMarkerInfoWindow $infoWindow The info window
     */
    public function setInfoWindow(MapMarkerInfoWindow $infoWindow)
    {
        $this->infoWindow = $infoWindow;
    }
    
    /**
     * Constructs a new instance of MapMarker.
     * 
     * @param type $lat The latitude
     * @param type $lng The longitude
     * @param type $icon The icon url
     * @param type $varName The javascript variable name
     */
    public function __construct($lat, $lng, $icon = null, $varName = '')
    {
        $this->coordinate = new MapCoordinate($lat, $lng);
        $this->icon = $icon;
        
        $this->varName = $varName;
        if ($this->varName === '') {
            $this->varName = sprintf('mapMarker%s', uniqid());
        }
    }
}
