<?php

namespace Vich\GeographicalBundle\Map\Renderer;

use Vich\GeographicalBundle\Map\Map;

/**
 * MapRendererInterface.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
interface MapRendererInterface
{
    /**
     * Renders the Map.
     * 
     * @param Vich\GeographicalBundle\Map\Map $map The map
     * @return string The html output
     */
    public function render(Map $map);
}
