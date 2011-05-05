<?php

namespace Vich\GeographicalBundle\Annotation;

use Vich\GeographicalBundle\Annotation\AnnotationInterface;

/**
 * GeographicalQuery.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class GeographicalQuery implements AnnotationInterface
{
    /**
     * @var string $method
     */
    private $method;
    
    /**
     * Gets the method name.
     * 
     * @return string The method name
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * Sets the method name.
     * 
     * @param string $value The method name
     */
    public function setMethod($value)
    {
        $this->method = $value;
    }
}
