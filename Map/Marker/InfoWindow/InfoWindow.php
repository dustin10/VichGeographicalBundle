<?php

namespace Vich\GeographicalBundle\Map\Marker\InfoWindow;

/**
 * InfoWindow.
 *
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class InfoWindow
{
    /**
     * @var string $varName
     */
    protected $varName;
    
    /**
     * @var integer $width
     */
    protected $width;
    
    /**
     * @var integer $height
     */
    protected $height;
    
    /**
     * @var string $title
     */
    protected $title;
    
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
     * Gets the info window width.
     * 
     * @return integer The width
     */
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * Sets the info window width.
     * 
     * @param integer $value The width
     */
    public function setWidth($value)
    {
        $this->width = $value;
    }
    
    /**
     * Gets the info window height.
     * 
     * @return integer The height
     */
    public function getHeight()
    {
        return $this->height;
    }
    
    /**
     * Sets the info window height.
     * 
     * @param integer $value The height
     */
    public function setHeight($value)
    {
        $this->width = $value;
    }
    
    /**
     * Gets the info window title.
     * 
     * @return string The title
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the info window title.
     * 
     * @param string $value The title
     */
    public function setTitle($value)
    {
        $this->title = $value;
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
        // escpape quotes and remove newlines and tabs
        $value = str_replace('"', '\"', $value);
        $value = preg_replace("/[\n\r\t]/", "", $value); 
        
        $this->content = $value;
    }
    
    /**
     * Constucts a new instance of MapMarkerInfoWindow.
     * 
     * @param string $content The content
     */
    public function __construct($content = '')
    {
        $this->varName = sprintf('iw%s', uniqid());
        $this->title = '';
        $this->width = 250;
        $this->height = 150;
        
        $this->setContent($content);
    }
}
