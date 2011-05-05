<?php

namespace Vich\GeographicalBundle\QueryService;

use Vich\GeographicalBundle\QueryService\QueryServiceInterface;
use Vich\GeographicalBundle\QueryService\QueryResult;

/**
 * GoogleQueryService.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class GoogleQueryService implements QueryServiceInterface
{
    /**
     * Queries google for the coordinates.
     * 
     * @param QueryResult $query The query
     */
    public function queryForCoordinates($query)
    {
        $result = new QueryResult();
        
        $formattedAddr = urlencode(str_replace( ' ', '+', $query ));
        $xml = simplexml_load_file('http://maps.google.com/maps/api/geocode/xml?address=' .
            $formattedAddr . '&sensor=false');

        if ((string)$xml->status === 'OK') {
            $latitude = (double)$xml->result->geometry->location->lat;
            $longitude = (double)$xml->result->geometry->location->lng;
            
            $result->setLatitude($latitude);
            $result->setLongitude($longitude);
        }
        
        return $result;
    }
}