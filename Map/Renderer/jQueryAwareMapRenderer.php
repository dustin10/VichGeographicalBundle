<?php

namespace Vich\GeographicalBundle\Map\Renderer;

use Vich\GeographicalBundle\Map\Map;
use Vich\GeographicalBundle\Map\Renderer\MapRenderer;

/**
 * jQueryAwareMapRenderer.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class jQueryAwareMapRenderer extends MapRenderer
{     
    /**
     * Renders the Map.
     * 
     * @param Vich\GeographicalBundle\Map\Map $map The map
     * @return string The html output
     */
    public function render(Map $map)
    {
        $html  = $this->renderContainer($map);
        $html .= $this->renderOpenScriptTag();
        $html .= $this->renderOpenjQueryTag();
        $html .= $this->renderMapVar($map);
        $html .= $this->renderBoundsVar($map);
        $html .= $this->renderMarkers($map);
        
        if ($map->getAutoZoom()) {
            $html .= $this->setFitToBounds($map);
        } else {
            $html .= $this->setMapCenter($map);
        }
        
        $html .= $this->renderClosejQueryTag();
        $html .= $this->renderCloseScriptTag();
        
        return $html;
    }
    
    /**
     * Renders the open jQuery ondomready function.
     * 
     * @return string The html
     */
    private function renderOpenjQueryTag()
    {
        return '$(function() {';
    }
    
    /**
     * Renders the closing jQuery ondomready function.
     * 
     * @return string The html
     */
    private function renderClosejQueryTag()
    {
        return '});';
    }
}