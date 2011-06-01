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
     * Returns the value of an option.
     *
     * @param string $name
     * @return mixed
     */
    public function getOption($name);

    /**
     * Sets the value of an option.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setOption($name, $value);

    /**
     * Renders the Map.
     *
     * @param Vich\GeographicalBundle\Map\Map $map The map
     * @return string The html output
     */
    public function render(Map $map);

    /**
     * Renders any javascripts that the renderer needs to use.
     * 
     * @return string The html output
     */
    public function renderJavascripts();
    
    /**
     * Renders any styelsheets that the renderer needs to use.
     * 
     * @return string The html output
     */
    public function renderStylesheets();
}
