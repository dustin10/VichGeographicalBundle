<?php

namespace Vich\GeographicalBundle\Map\Coordinate;

/**
 * MapCoordinate.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class MapCoordinate
{
    /**
     * @var double $lat
     */
    private $lat;
    
    /**
     * @var double $lng
     */
    private $lng;
    
    /**
     * Gets the latitude.
     * 
     * @return double The latitude
     */
    public function getLat()
    {
        return $this->lat;
    }
    
    /**
     * Sets the latitude.
     * 
     * @param double $value The latitude
     */
    public function setLat($value)
    {
        $this->lat = $value;
    }
    
    /**
     * Gets the longitude.
     * 
     * @return double The longitude
     */
    public function getLng()
    {
        return $this->lng;
    }
    
    /**
     * Sets the longitude.
     * 
     * @param double $value The longitude
     */
    public function setLng($value)
    {
        $this->lng = $value;
    }
    
    /**
     * Constructs a new instance of MapCoordinate.
     * 
     * @param double $lat The latitude
     * @param double $lng The longitude
     */
    public function __construct($lat = 0, $lng = 0)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }
}
