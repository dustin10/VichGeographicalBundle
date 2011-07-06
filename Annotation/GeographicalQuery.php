<?php

namespace Vich\GeographicalBundle\Annotation;

/**
 * GeographicalQuery.
 * 
 * @Annotation
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class GeographicalQuery
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
    
    /**
     * Constructs a new instance of GeographicalQuery.
     * 
     * @param array $values The option values
     */
    public function __construct(array $values) {
        
    }
}
