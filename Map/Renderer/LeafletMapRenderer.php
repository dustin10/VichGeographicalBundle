<?php

namespace Vich\GeographicalBundle\Map\Renderer;

use Vich\GeographicalBundle\Map\Map;
use Vich\GeographicalBundle\Map\MapMarker;
use Vich\GeographicalBundle\Map\Renderer\AbstractMapRenderer;

/**
 * LeafletMapRenderer.
 * 
 * @author Henrik Westphal <henrik.westphal@gmail.com>
 */
class LeafletMapRenderer extends AbstractMapRenderer
{
    static protected $isCloudMadeRendered = false;

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
        $html .= $this->renderCloudMadeVar($map);
        $html .= $this->renderMapVar($map);

        if ($map->getAutoZoom()) {
            $html .= $this->renderBoundsVar($map);
        }

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
            '<script type="text/javascript" src="http://leaflet.cloudmade.com/dist/leaflet.js"></script>',
        );
        
        return implode('', $scripts);
    }

    /**
     * Renders any stylesheets that the renderer needs to use.
     *
     * @return string The html output
     */
    public function renderStylesheets()
    {
        $stylesheets = array(
            '<link rel="stylesheet" href="http://leaflet.cloudmade.com/dist/leaflet.css" />',
            '<!--[if lte IE 8]><link rel="stylesheet" href="http://leaflet.cloudmade.com/dist/leaflet.ie.css" /><![endif]-->',
        );

        return implode('', $stylesheets);
    }

    /**
     * Renders the map container.
     * 
     * @param Map $map The map
     * @return string The html
     */
    protected function renderContainer(Map $map)
    {
        $width = is_numeric(substr($map->getWidth(), -1)) ? "{$map->getWidth()}px" : $map->getWidth();
        $height = is_numeric(substr($map->getHeight(), -1)) ? "{$map->getHeight()}px" : $map->getHeight();
        
        return sprintf('<div id="%s" style="width: %s; height: %s;"></div>',
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
     * Renders the cloudmade var.
     * 
     * @param Map $map The map
     * @return string The html
     */
    protected function renderCloudMadeVar(Map $map)
    {
        if (static::$isCloudMadeRendered) {
            return '';
        }
        
        static::$isCloudMadeRendered = true;
        
        $apiKey = $this->getOption('leaflet_api_key');
        
        return sprintf("var cloudmadeUrl = 'http://{s}.tile.cloudmade.com/%s/997/256/{z}/{x}/{y}.png', cloudmadeAttribution = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade', cloudmade = new L.TileLayer(cloudmadeUrl, {maxZoom: 18, attribution: cloudmadeAttribution});",
            $apiKey
        );

    }

    /**
     * Renders the map var.
     *
     * @param Map $map The map
     * @return string The html
     */
    protected function renderMapVar(Map $map)
    {
        return sprintf("var %s = new L.Map('%s'); map.addLayer(cloudmade);",
            $map->getVarName(),
            $map->getContainerId()
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
        return sprintf('var %s = new L.LatLngBounds();',
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
            $html .= sprintf('var %s = new L.Marker(new L.LatLng(%s, %s)); %s.addLayer(%s);',
                $marker->getVarName(),
                $marker->getCoordinate()->getLat(),
                $marker->getCoordinate()->getLng(),
                $map->getVarName(),
                $marker->getVarName()
            );
            
            if ($map->getAutoZoom()) {
                $html .= sprintf('%s.extend(%s.getLatLng());',
                    $map->getVarName().'Bounds',
                    $marker->getVarName()
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
        return sprintf('%s.setView(new L.LatLng(%s, %s), %u);',
            $map->getVarName(),
            $map->getCenter()->getLat(),
            $map->getCenter()->getLng(),
            $map->getZoom()
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
