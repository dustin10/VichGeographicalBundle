<?php

namespace Vich\GeographicalBundle\Map\Marker\Icon;

/**
 * IconGeneratorInterface.
 *
 * @author Dustin Dobervich
 */
interface IconGeneratorInterface
{
    /**
     * Generates the map marker icon url for the specified entity.
     * 
     * @param object $obj The entity
     * @return string The map marker icon url
     */
    function generateIcon($obj);
}
