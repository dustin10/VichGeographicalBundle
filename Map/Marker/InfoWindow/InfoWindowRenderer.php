<?php

namespace Vich\GeographicalBundle\Map\Marker\InfoWindow;

use Vich\GeographicalBundle\Map\Marker\InfoWindow\InfoWindowRendererInterface;
use Vich\GeographicalBundle\Map\Marker\InfoWindow\InfoWindow;

/**
 * InfoWindowBuilder.
 *
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class InfoWindowRenderer implements InfoWindowRendererInterface
{
    /**
     * @var object $templating 
     */
    private $templating;
    
    /**
     * @var string $templateName
     */
    private $templateName;
    
    /**
     * Constructs a new instance of InfoWindowRenderer.
     * 
     * @param object $templating The templating engine
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
        
        return new InfoWindow($content);
    }
}
