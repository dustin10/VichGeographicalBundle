<?php

namespace Vich\GeographicalBundle\Map\Marker\InfoWindow;

/**
 * InfoWindowRendererInterface.
 *
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
interface InfoWindowRendererInterface
{
    /**
     * Builds the info window for the object.
     * 
     * @param object $obj
     * @return InfoWindow The info window
     */
    function build($obj);
}
