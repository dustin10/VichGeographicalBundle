<?php

namespace Vich\GeographicalBundle\Map\Builder;

use Vich\GeographicalBundle\Map\Builder\InfoWindowBuilderInterface;
use Vich\GeographicalBundle\Map\MapMarkerInfoWindow;

/**
 * MapMarkerInfoWindowBuilder.
 *
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class MapMarkerInfoWindowBuilder implements MapMarkerInfoWindowBuilderInterface
{
    /**
     * @var Symfony\Component\Templating\EngineInterface $templating 
     */
    private $templating;
    
    /**
     * @var string $templateName
     */
    private $templateName;
    
    /**
     * Constructs a new instance of MapMarkerInfoWindowBuilder.
     * 
     * @param Symfony\Component\Templating\EngineInterface $templating The templating engine
     * @param string $templateName The template name
     */
    public function __construct($templating, $templateName)
    {
        $this->templating = $templating;
        $this->templateName = $templateName;
    }
    
    /**
     * {@inheritDoc}
     */
    public function build($obj)
    {
        $content = $this->templating->render($this->templateName, array('obj' => $obj));
        
        // escpape quotes and remove newlines and tabs
        $content = str_replace('"', '\"', $content);
        $content = preg_replace("/[\n\r\t]/","", $content); 
        
        return new MapMarkerInfoWindow($content);
    }
}
