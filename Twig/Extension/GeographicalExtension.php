<?php

namespace Vich\GeographicalBundle\Twig\Extension;

use Vich\GeographicalBundle\Templating\Helper\MapHelper;

/**
 * GeographicalExtension.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class GeographicalExtension extends \Twig_Extension
{   
    /**
     * @var MapHelper $helper
     */
    private $helper;
    
    /**
     * Constructs a new instance of GeographicalExtension.
     * 
     * @param MapHelper $helper
     */
    public function __construct(MapHelper $helper)
    {
        $this->helper = $helper;
    }
    
    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'vich_geographical';
    }
    
    /**
     * Returns a list of twig functions.
     *
     * @return array An array
     */
    public function getFunctions()
    {
        $names = array(
            'vichgeo_include_js'  => 'includeJavascripts',
            'vichgeo_include_css' => 'includeStylesheets',
            'vichgeo_map'         => 'renderMap',
            'vichgeo_map_for'     => 'renderMapWithEntities'
        );
        
        $funcs = array();
        foreach ($names as $twig => $local) {
            $funcs[] = new \Twig_SimpleFunction($twig, [$this, $local], ['is_safe' => ['html']]);
        }
        
        return $funcs;
    }
    
    /**
     * Includes the necessary javascripts for the map renderer.
     * 
     * @return string The html output
     */
    public function includeJavascripts()
    {
        return $this->helper->renderJavascripts();
    }
    
    /**
     * Includes the necessary stylesheets for the map renderer.
     * 
     * @return string The html output
     */
    public function includeStylesheets()
    {
        return $this->helper->renderStylesheets();
    }
    
    /**
     * Renders a Map.
     * 
     * @param type $alias The Map alias
     * @return string The html output
     */
    public function renderMap($alias)
    {
        return $this->helper->render($alias);
    }
    
    /**
     * Renders a Map with the specified entity or array of entities.
     * 
     * @param string $alias The Map alias
     * @param object $obj The object
     * @return string The html output
     */
    public function renderMapWithEntities($alias, $obj)
    {
        if (empty($obj)) {
            return $this->renderMap($alias);
        }
        
        return $this->helper->prepareAndRender($alias, $obj);
    }
}