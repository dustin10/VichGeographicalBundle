<?php

namespace Vich\GeographicalBundle\Map\Renderer;

use Vich\GeographicalBundle\Map\Map;
use Vich\GeographicalBundle\Map\MapMarker;
use Vich\GeographicalBundle\Map\Renderer\AbstractMapRenderer;

/**
 * GoogleMapRenderer.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class GoogleMapRenderer extends AbstractMapRenderer
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
        $html .= $this->renderMapVar($map);
        $html .= $this->renderBoundsVar($map);
        $html .= $this->renderMarkers($map);
        
        if ($map->getAutoZoom()) {
            $html .= $this->setFitToBounds($map);
        } else {
            $html .= $this->setMapCenter($map);
        }
        
        $html .= $this->renderCloseScriptTag();
        
        return $html;
    }
    
    /**
     * Renders any javascripts that the renderer needs to use.
     * 
     * @return string The html output
     */
    public function renderJavascripts()
    {
        $scripts = array(
            '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>'
        );
        
        return implode('', $scripts);
    }
    
    /**
     * Renders the map container.
     * 
     * @param Map $map The map
     * @return string The html
     */
    protected function renderContainer(Map $map)
    {
        return sprintf('<div id="%s" style="width: %spx; height: %spx;"></div>',
            $map->getContainerId(),
            $map->getWidth(),
            $map->getHeight()
        );
    }
    
    /**
     * Renders an open script tag.
     * 
     * @return string The html
     */
    protected function renderOpenScriptTag()
    {
        return '<script type="text/javascript">';
    }
    
    /**
     * Renders the map var.
     * 
     * @param Map $map The map
     * @return type The html
     */
    protected function renderMapVar(Map $map)
    {
        return sprintf('var %s = new google.maps.Map(document.getElementById("%s"), %s);',
            $map->getVarName(),
            $map->getContainerId(),
            json_encode($map->getMapOptions())
        );
             
    }
    
    /**
     * Renders the map bounds var.
     * 
     * @param Map $map The map
     * @return string The html
     */
    protected function renderBoundsVar(Map $map)
    {
        return sprintf('var %s = new google.maps.LatLngBounds();',
            $map->getVarName().'Bounds'
        );
    }
    
    /**
     * Renders the markers for the map.
     * 
     * @param Map $map The map
     * @return string The html
     */
    protected function renderMarkers(Map $map)
    {
        $html = '';
        
        foreach ($map->getMarkers() as $marker) {
            $html .= sprintf('var %s = new google.maps.Marker({ position: new google.maps.LatLng(%s, %s), map: %s });',
                $marker->getVarName(),
                $marker->getCoordinate()->getLat(),
                $marker->getCoordinate()->getLng(),
                $map->getVarName()
            );
            
            if ($map->getAutoZoom()) {
                $html .= sprintf('%s.extend(new google.maps.LatLng(%s, %s));',
                    $map->getVarName().'Bounds',
                    $marker->getCoordinate()->getLat(),
                    $marker->getCoordinate()->getLng()
                );
            }
        }
        
        return $html;
    }
    
    /**
     * Sets the map to auto zoom to the specified bounds.
     * 
     * @param Map $map The map
     * @return string The html
     */
    protected function setFitToBounds(Map $map)
    {
        return sprintf('%s.fitBounds(%s);',
            $map->getVarName(),
            $map->getVarName().'Bounds'
        );
    }
    
    /**
     * Sets the center of the map.
     * 
     * @param Map $map
     * @return string The html 
     */
    protected function setMapCenter(Map $map)
    {
        return sprintf('%s.setCenter(new google.maps.LatLng(%s, %s));',
            $map->getVarName(),
            $map->getCenter()->getLat(),
            $map->getCenter()->getLng()
        );
    }
    
    /**
     * Renders a closing script tag.
     * 
     * @return string The html
     */
    protected function renderCloseScriptTag()
    {
        return '</script>';
    }
}