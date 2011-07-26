<?php

namespace Vich\GeographicalBundle\Map;

/**
 * MapMarkerInfoWindow.
 *
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class MapMarkerInfoWindow
{
    /**
     * @var string $varName
     */
    protected $varName;
    
    /**
     * @var string $content
     */
    protected $content;
    
    /**
     * Gets the variable name of the info window
     * 
     * @return string The variable name
     */
    public function getVarName()
    {
        return $this->varName;
    }
    
    /**
     * Sets the variable name of the info window.
     * 
     * @param string $value The variable name
     */
    public function setVarName($value)
    {
        $this->varName = $value;
    }
    
    /**
     * Gets the content of the info window
     * 
     * @return string The content
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Sets the content of the info window
     * 
     * @return string $value The content
     */
    public function setContent($value)
    {
        $this->content = $value;
    }
    
    /**
     * Constucts a new instance of MapMarkerInfoWindow.
     * 
     * @param string $content The content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
        $this->varName = sprintf('iw%s', uniqid());
    }
}
