<?php

namespace Vich\GeographicalBundle\Map\Builder;

/**
 * MapMarkerInfoWindowBuilderInterface.
 *
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
interface MapMarkerInfoWindowBuilderInterface
{
    /**
     * Builds the info window for the object.
     * 
     * @param object $obj
     * @return Vich\GeographicalBundle\Map\MapMarkerInfoWindow The info window
     */
    function build($obj);
}
