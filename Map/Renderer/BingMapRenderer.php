<?php

namespace Vich\GeographicalBundle\Map\Renderer;

use Vich\GeographicalBundle\Map\Map;
use Vich\GeographicalBundle\Map\MapMarker;
use Vich\GeographicalBundle\Map\Renderer\AbstractMapRenderer;

/**
 * BingMapRenderer.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class BingMapRenderer extends AbstractMapRenderer
{
    /**
     * Renders any javascripts that the renderer needs to use.
     * 
     * @return string The html output
     */
    public function renderJavascripts()
    {
       $scripts = array(
            '<script charset="UTF-8" type="text/javascript" src="http://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0"></script>'
        );
        
        return implode('', $scripts);
    }
    
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
        $html .= $this->renderPinsVar($map);
        $html .= $this->renderMarkers($map);
        
        if ($map->getAutoZoom()) {
            $html .= $this->setViewFromPins($map);
        } else {
            $html .= $this->setMapZoom($map);
            $html .= $this->setMapCenter($map);
        }
        
        $html .= $this->renderCloseScriptTag();
        
        return $html;
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
            '<div id="%s" style="position: relative; width: %s; height: %s;"></div>',
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
            'var %s = new Microsoft.Maps.Map(document.getElementById("%s"), %s);',
            $map->getVarName(),
            $map->getContainerId(),
            sprintf(
                '{ credentials: "%s", mapTypeId: %s }',
                $this->getOption('bing_api_key'),
                $this->transformMapType($map->getMapType()),
                $map->getZoom()
            )
        );
    }
    
    /**
     * Renders the pins variable to hold locations of pins.
     * 
     * @param Map $map The map
     * @return string The html
     */
    protected function renderPinsVar(Map $map)
    {
        return sprintf(
            'var %s = [];',
            $this->getMapPinsVarName($map)
        );
    }
    
    /**
     * Renders the maps markers.
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
                'var %s = new Microsoft.Maps.Pushpin(new Microsoft.Maps.Location(%s, %s)); %s.entities.push(%s);',
                $marker->getVarName(),
                $lat,
                $lng,
                $map->getVarName(),
                $marker->getVarName()
            );
            
            if (null !== $marker->getInfoWindow()) {
                $html .= sprintf(
                    'var %s = new Microsoft.Maps.Infobox(%s.getLocation(), {offset:new Microsoft.Maps.Point(-%s,%s), ' .
                    'title:"%s", htmlContent:"<div style=\"padding: 10px; width: %spx; height: %spx; border-radius: 5px; ' .
                    'border: solid 1px #777777; background-color: #FFFFFF;\"><div style=\"text-align: right; ' .
                    'margin-bottom: 10px;\"><a href=\"#\" onclick=\"return vichGeoCloseInfobox(%s);\" style=\"font-size: 10px; ' .
                    'color: #777777;\" id=\"vich_geo_bing_close\">X</a></div>%s</div>", visible:false, width:%s, height:%s});'.
                    'Microsoft.Maps.Events.addHandler(%s, "click", function(e) { %s.setOptions({visible:true}); }); ' .
                    '%s.entities.push(%s);',
                    $marker->getInfoWindow()->getVarName(),
                    $marker->getVarName(),
                    $marker->getInfoWindow()->getWidth() / 2.0,
                    $marker->getInfoWindow()->getHeight() + 50,
                    $this->escapeQuotes($marker->getInfoWindow()->getTitle()),
                    $marker->getInfoWindow()->getWidth(),
                    $marker->getInfoWindow()->getHeight(),
                    $marker->getInfoWindow()->getVarName(),
                    $marker->getInfoWindow()->getContent(),
                    $marker->getInfoWindow()->getWidth(),
                    $marker->getInfoWindow()->getHeight(),
                    $marker->getVarName(),
                    $marker->getInfoWindow()->getVarName(),
                    $map->getVarName(),
                    $marker->getInfoWindow()->getVarName()
                );
                
                $html .= 'function vichGeoCloseInfobox(infobox) { infobox.setOptions({ visible: false }); return false; }';
            }
            
            $html .= sprintf(
                '%s.push(new Microsoft.Maps.Location(%s, %s));',
                $this->getMapPinsVarName($map),
                $lat,
                $lng
            );
        }
        
        return $html;
    }
    
    /**
     * Sets the view of the map based on the pins.
     * 
     * @param Map $map The map
     * @return string The html
     */
    protected function setViewFromPins(Map $map)
    {
        return sprintf(
            '%s.setView({ bounds: Microsoft.Maps.LocationRect.fromLocations(%s) });',
            $map->getVarName(),
            $this->getMapPinsVarName($map)
        );
    }
    
    /**
     * Sets the zoom level for the map.
     * 
     * @param Map $map The map
     * @return string The html
     */
    protected function setMapZoom(Map $map)
    {
        return sprintf(
            '%s.setView({ zoom: %s });',
            $map->getVarName(),
            $map->getZoom()
        );
    }
    
    /**
     * Renders the script to set the center of the map.
     * 
     * @param Map $map The map
     * @return string The html
     */
    protected function setMapCenter(Map $map)
    {
        if (null === $map->getCenter()) {
            return;
        }
        
        return sprintf(
            '%s.setView({ center: new Microsoft.Maps.Location(%s, %s) });', 
            $map->getVarName(),
            $map->getCenter()->getLat(),
            $map->getCenter()->getLng()
        );
    }
    
    /**
     * Transforms the map type to the Bing map type.
     * 
     * @param string $type The type
     * @return string The map type javascript
     */
    protected function transformMapType($type)
    {
        switch ($type) {
            
            case Map::TYPE_SATELLITE:
                return 'Microsoft.Maps.MapTypeId.aerial';
            
            default:
                return 'Microsoft.Maps.MapTypeId.road';
                
        }
    }
    
    /**
     * Gets the pins variable name for the map.
     * 
     * @param Map $map The map
     * @return string The var name
     */
    protected function getMapPinsVarName(Map $map)
    {
        return $map->getVarName() . 'Pins';
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
     * Escapes the quotes in a string.
     * 
     * @param string $text The string to escape
     * @return string The result
     */
    protected function escapeQuotes($text)
    {
        return str_replace('"', '\"', $text);
    }
}