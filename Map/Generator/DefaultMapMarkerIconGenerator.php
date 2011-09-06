<?php

namespace Vich\GeographicalBundle\Map\Generator;

use Vich\GeographicalBundle\Map\Generator\MapMarkerIconGeneratorInterface;

/**
 * DefaultMapMarkerIconGenerator.
 *
 * @author Dustin Dobervich
 */
class DefaultMapMarkerIconGenerator implements MapMarkerIconGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function generateIcon($obj)
    {
        return null;
    }
}
