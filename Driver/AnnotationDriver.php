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
     * Gets the Geograhpical annotation for the specified object.
     * 
     * @param object $obj The object
     * @return Geographical The geographical annotation
     */
    public function getGeographicalAnnotation($obj)
    {   
        if (!is_object($obj)) {
            throw new \InvalidArgumentException();
        }

        $refClass = new \ReflectionClass($obj);

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
}
