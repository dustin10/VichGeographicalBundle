<?php

namespace Vich\GeographicalBundle\Map;

use Vich\GeographicalBundle\Map\MapCoordinate;

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
     * @var string $infoWindow
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
     * Gets the infoWindow.
     *
     * @return string The icon url
     */
    public function getInfoWindow()
    {
        return $this->infoWindow;
    }

    /**
     * Sets the infoWindow.
     *
     * @param string $value The icon url
     */
    public function setInfoWindow($value)
    {
        $this->infoWindow = $value;
    }
    
    /**
     * Constructs a new instance of MapMarker.
     * 
     * @param type $lat The latitude
     * @param type $lng The longitude
     * @param type $icon The icon url
     * @param type $varName The javascript variable name
     */
    public function __construct($lat, $lng, $icon = null, $varName = 'mapMarker')
    {
        $this->coordinate = new MapCoordinate($lat, $lng);
        $this->icon = $icon;
        $this->varName = $varName;
    }
}
