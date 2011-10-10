<?php

namespace Vich\GeographicalBundle\Driver;

use Doctrine\Common\Annotations\Reader;
use Vich\GeographicalBundle\Adapter\AdapterInterface;

/**
 * AnnotationDriver.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class AnnotationDriver
{
    /**
     * @var Reader $reader
     */
    protected $reader;
    
    /**
     * @var AdapterInterface $adapter
     */
    protected $adapter;
    
    /**
     * @var string $geoClass
     */
    protected $geoClass;
    
    /**
     * @var string $geoQueryClass
     */
    protected $geoQueryClass;
    
    /**
     * Constructs a new intsance of AnnotationDriver.
     * 
     * @param Reader $reader The annotation reader
     * @param AdapterInterface $apapter The adapter
     * @param string $geoClass The geographical annotaion class name
     * @param string $geoQueryClass The geographical query annotaion class name
     */
    public function __construct(Reader $reader, AdapterInterface $adapter, $geoClass, $geoQueryClass)
    {
        $this->reader = $reader;
        $this->adapter = $adapter;
        $this->geoClass = $geoClass;
        $this->geoQueryClass = $geoQueryClass;
    }
    
    /**
     * Gets the Geographical annotation for the specified object.
     * 
     * @param object $obj The object
     * @return Geographical The geographical annotation
     */
    public function readGeoAnnotation($obj)
    {   
        if (!is_object($obj)) {
            throw new \InvalidArgumentException('The variable is not an object.');
        }

        $refClass = $this->resolveProxy($obj);
        
        return $this->reader->getClassAnnotation($refClass, $this->geoClass);    
    }
    
    /**
     * Gets the GeographicalQuery annotation for the specified object.
     * 
     * @param object $obj The object
     * @return GeographicalQuery The geographical query annotation
     */
    public function readGeoQueryAnnotation($obj)
    {   
        if (!is_object($obj)) {
            throw new \InvalidArgumentException();
        }
        
        $refClass = new \ReflectionClass($obj);
        
        foreach ($refClass->getMethods() as $method) {
            $annot = $this->reader->getMethodAnnotation($method, $this->geoQueryClass);
            if (null !== $annot) {
                $annot->setMethod($method->getName());
                return $annot;
            }
        }
        
        return null;
    }
    
    /**
     * Tests an object to see if it is a proxy, if so return the \ReflectionClass 
     * object representing its parent.
     * 
     * @param object $obj The object to test
     * @return \ReflectionClass The reflection class
     */
    protected function resolveProxy($obj)
    {
        if ($this->adapter->isProxy($obj)) {
            return new \ReflectionClass(get_parent_class($obj));
        }
        
        return new \ReflectionClass($obj);
    }
}
