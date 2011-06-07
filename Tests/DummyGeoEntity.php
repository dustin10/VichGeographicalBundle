<?php

namespace Vich\GeographicalBundle\Tests;

use Vich\GeographicalBundle\Annotation as Vich;

/**
 * @Vich\Geographical(on="update")
 */
class DummyGeoEntity
{
    /**
     * @var double $latitude
     */
    protected $latitude = 0;
    
    /**
     * @var double $longitude
     */
    protected $longitude = 0;
    
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
    
    /**
     * @Vich\GeographicalQuery
     */
    public function getAddress()
    {
        return '98 Boulevard Victor Hugo 92110 Clichy, France';
    }
}
