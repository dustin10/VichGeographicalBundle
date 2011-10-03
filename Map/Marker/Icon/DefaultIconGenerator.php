<?php

namespace Vich\GeographicalBundle\Map\Marker\Icon;

use Vich\GeographicalBundle\Map\Marker\Icon\IconGeneratorInterface;

/**
 * DefaultIconGenerator.
 *
 * @author Dustin Dobervich
 */
class DefaultIconGenerator implements IconGeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function generateIcon($obj)
    {
        return null;
    }
}
