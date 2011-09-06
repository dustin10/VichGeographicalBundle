<?php

namespace Vich\GeographicalBundle\Map\Generator;

/**
 * MapMarkerIconGeneratorInterface.
 *
 * @author Dustin Dobervich
 */
interface MapMarkerIconGeneratorInterface
{
    /**
     * Generates the map marker icon url for the specified entity.
     * 
     * @param object $obj The entity
     * @return string The map marker icon url
     */
    function generateIcon($obj);
}
