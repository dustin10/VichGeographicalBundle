<?php

namespace Vich\GeographicalBundle\QueryService;

/**
 * QueryResult.
 * 
 * @author Dustin Dobervich
 */
class QueryResult
{
    /**
     * @var double $latitude
     */
    private $latitude = 0;
    
    /**
     * @var double $longitude
     */
    private $longitude = 0;
    
    /**
     * Gets the latitude.
     * 
     * @return double The latitude
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    /**
     * Sets the latitude.
     * 
     * @param double $value The latitude
     */
    public function setLatitude($value)
    {
        $this->latitude = $value;
    }
    
    /**
     * Gets the longitude.
     * 
     * @return double The longitude
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    
    /**
     * Sets the longitude.
     * 
     * @param double $value The longitude
     */
    public function setLongitude($value)
    {
        $this->longitude = $value;
    }
}