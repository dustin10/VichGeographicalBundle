<?php

namespace Vich\GeographicalBundle\Annotation;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Annotations\Parser;
use Doctrine\Common\Annotations\AnnotationReader as BaseAnnotationReader;

/**
 * AnnotationReader.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class AnnotationReader extends BaseAnnotationReader
{
    /**
     * Constructs a new instance of AnnotationReader.
     * 
     * @param Cache $cache The cache
     * @param Parser $parser The annotation parser
     */
    public function __construct(Cache $cache = null, Parser $parser = null)
    {
        parent::__construct($cache, $parser);
    }
    
    /**
     * Gets the Geograhpical annotation for the specified class.
     * 
     * @param ReflectionClass $class The class
     * @return Form The form annotation
     */
    public function getGeographicalAnnotation(\ReflectionClass $class)
    {
        $this->setAnnotationCreationFunction(function($name, $values)
        {
            $r = new \ReflectionClass($name);
            if (!$r->implementsInterface('Vich\GeographicalBundle\Annotation\AnnotationInterface')) {
                return null;
            }
            
            $annot = new $name();
            foreach ($values as $key => $value) {
                $method = 'set'.$key;
                if (!method_exists($annot, $method)) {
                    throw new \BadMethodCallException(
                        sprintf(
                            "Unknown annotation attribute '%s' for '%s'.",
                            ucfirst($key),
                            get_class($this)
                        )
                    );
                }
                
                $annot->$method($value);
            }

            return $annot;
        });
        
        return $this->getClassAnnotation($class, 'Vich\GeographicalBundle\Annotation\Geographical');
    }
}