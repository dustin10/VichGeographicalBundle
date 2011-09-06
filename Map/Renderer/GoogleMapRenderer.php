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
        $width = is_numeric(substr($map->getWidth(), -1)) ?
            $map->getWidth() . 'px' : $map->getWidth();
        $height = is_numeric(substr($map->getHeight(), -1)) ?
            $map->getHeight() . 'px' : $map->getHeight();
        
        return sprintf(
            '<div id="%s" style="width: %s; height: %s;"></div>',
            $map->getContainerId(),
            $width,
            $height
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
     * @return string The html
     */
    protected function renderMapVar(Map $map)
    {
        return sprintf(
            'var %s = new google.maps.Map(document.getElementById("%s"), %s);',
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
        return sprintf(
            'var %s = new google.maps.LatLngBounds();',
            $this->getBoundsVarName($map)
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
            $lat = $marker->getCoordinate()->getLat();
            $lng = $marker->getCoordinate()->getLng();
            
            if (is_null($lat) || is_null($lng)) {
                continue;
            }
            
            $html .= sprintf(
                'var %s = new google.maps.Marker({ position: new google.maps.LatLng(%s, %s), map: %s });',
                $marker->getVarName(),
                $lat,
                $lng,
                $map->getVarName()
            );
            
            if (null !== $marker->getInfoWindow()) {
                $html .= sprintf(
                    'var %s = new google.maps.InfoWindow({
                    content: "%s"}); google.maps.event.addListener(%s, "click", function (e) {%s.open (%s, %s); });',
                    $marker->getInfoWindow()->getVarName(),
                    $marker->getInfoWindow()->getContent(),
                    $marker->getVarName(),
                    $marker->getInfoWindow()->getVarName(),
                    $map->getVarName(),
                    $marker->getVarName()
                );
                
                if (null !== $marker->getIcon()) {
                    $html .= sprintf(
                        '%s.setIcon("%s");',
                        $marker->getVarName(),
                        $marker->getIcon()
                    );
                }
            }
            
            if ($map->getAutoZoom()) {
                $html .= sprintf(
                    '%s.extend(new google.maps.LatLng(%s, %s));',
                    $this->getBoundsVarName($map),
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
        return sprintf(
            '%s.fitBounds(%s);',
            $map->getVarName(),
            $this->getBoundsVarName($map)
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
        return sprintf(
            '%s.setCenter(new google.maps.LatLng(%s, %s));',
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
    
    /**
     * Gets the bounds variable name for the map.
     * 
     * @param Map $map The map
     * @return string The var name
     */
    protected function getBoundsVarName(Map $map)
    {
        return $map->getVarname() . 'Bounds';
    }
}