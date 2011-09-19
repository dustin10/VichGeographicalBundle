<?php

namespace Vich\GeographicalBundle\Driver;

use Doctrine\Common\Annotations\Reader;

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
    private $reader;
    
    /**
     * Constructs a new intsance of AnnotationDriver.
     * 
     * @param Reader $reader The annotation reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }
    
    /**
     * Gets the Geographical annotation for the specified object.
     * 
     * @param object $obj The object
     * @return Geographical The geographical annotation
     */
    public function getGeographicalAnnotation($obj)
    {   
        if (!is_object($obj)) {
            throw new \InvalidArgumentException();
        }

        $refClass = $this->resolveProxy($obj);
        
        return $this->reader->getClassAnnotation($refClass, 'Vich\GeographicalBundle\Annotation\Geographical');    
    }
    
    /**
     * Gets the GeographicalQuery annotation for the specified object.
     * 
     * @param object $obj The object
     * @return GeographicalQuery The geographical query annotation
     */
    public function getGeographicalQueryAnnotation($obj)
    {   
        if (!is_object($obj)) {
            throw new \InvalidArgumentException();
        }
        
        $refClass = new \ReflectionClass($obj);
        
        foreach ($refClass->getMethods() as $method) {
            $annot = $this->reader->getMethodAnnotation($method, 'Vich\GeographicalBundle\Annotation\GeographicalQuery');
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
     * @param type $obj The object to test
     * @return \ReflectionClass The reflection class
     */
    protected function resolveProxy($obj)
    {
        // this needs to be refactored as there is a chance that 'Proxies' is not 
        // the configured namespace, but will work for now...
        $refClass = new \ReflectionClass($obj);
        if (false !== strpos($refClass->getName(), 'Proxies\\')) {
            $refClass = new \ReflectionClass(get_parent_class($obj));
        }
        
        return $refClass;
    }
}
