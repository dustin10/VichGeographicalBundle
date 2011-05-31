<?php

namespace Vich\GeographicalBundle\Map\Renderer;

use Vich\GeographicalBundle\Map\Map;
use Vich\GeographicalBundle\Map\Renderer\MapRendererInterface;

/**
 * AbstractMapRenderer.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
abstract class AbstractMapRenderer implements MapRendererInterface
{
    /**
     * Renders the Map.
     * 
     * @param Vich\GeographicalBundle\Map\Map $map The map
     * @return string The html output
     */
    public function render(Map $map) { }
    
    /**
     * Renders any javascripts that the renderer needs to use.
     * 
     * @return string The html output
     */
    public function renderJavascripts() { }
    
    /**
     * Renders any styelsheets that the renderer needs to use.
     * 
     * @return string The html output
     */
    public function renderStylesheets() { }
}
