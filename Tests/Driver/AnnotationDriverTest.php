<?php

namespace Vich\GeographicalBundle\Tests\Driver;

use Doctrine\Common\Annotations\Reader;
use Vich\GeographicalBundle\Driver\AnnotationDriver;

/**
 * AnnotationDriverTest.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Doctrine\Common\Annotations\Reader $reader
     */
    protected $reader;
    
    /**
     * @var Vich\GeographicalBundle\Driver\AnnotationDriver $driver
     */
    protected $driver;
    
    /**
     * @var Vich\GeographicalBundle\Tests\DummyGeoEntity $geographicalEntity
     */
    protected $geographicalEntity;
    
    /**
     * @var Vich\GeographicalBundle\Annotation\Geographical $geographicalAnnotation
     */
    protected $geographicalAnnotation;
    
    /**
     * @var Vich\GeographicalBundle\Annotation\GeographicalQuery $geographicalQueryAnnotation
     */
    protected $geographicalQueryAnnotation;
    
    /**
     * Sets up the test.
     */
    protected function setUp()
    {
        $this->reader = $this->getMockReader();
        $this->driver = $this->getDriver($this->reader);
        $this->geographicalEntity = $this->getMockGeographicalEntity();
        $this->geographicalAnnotation = $this->getMockGeographicalAnnotation();
        $this->geographicalQueryAnnotation = $this->getMockGeographicalQueryAnnotation();
    }
    
    /**
     * Test the readGeoAnnotation method of the AnnotationDriver.
     */
    public function testGetGeographicalAnnotation()
    {
        $this->geographicalAnnotation
               ->expects($this->once())
               ->method('getLat')
               ->will($this->returnValue('latitude'));
        
        $this->geographicalAnnotation
               ->expects($this->once())
               ->method('getLng')
               ->will($this->returnValue('longitude'));
        
        $this->reader
               ->expects($this->once())
               ->method('getClassAnnotation')
               ->with($this->isInstanceOf('\ReflectionClass'), $this->equalTo('Vich\GeographicalBundle\Annotation\Geographical'))
               ->will($this->returnValue($this->geographicalAnnotation));
        
        $annot = $this->driver->readGeoAnnotation($this->geographicalEntity);
        
        $this->assertNotNull($annot);
        $this->assertEquals('latitude', $annot->getLat());
        $this->assertEquals('longitude', $annot->getLng());
    }
    
    /**
     * Test the readGeoQueryAnnotation method of the AnnotationDriver.
     */
    public function testReadGeoQueryAnnotation()
    {
        $this->geographicalQueryAnnotation
               ->expects($this->once())
               ->method('getMethod')
               ->will($this->returnValue('getAddress'));
        
        $this->reader
               ->expects($this->once())
               ->method('getMethodAnnotation')
               ->with($this->isInstanceOf('\ReflectionMethod'), $this->equalTo('Vich\GeographicalBundle\Annotation\GeographicalQuery'))
               ->will($this->returnValue($this->geographicalQueryAnnotation));
        
        $annot = $this->driver->readGeoQueryAnnotation($this->geographicalEntity);
        
        $this->assertNotNull($annot);
        $this->assertEquals('getAddress', $annot->getMethod());
    }
    
    /**
     * Gets the mock reader object.
     * 
     * @return Doctrine\Common\Annotations\Reader The reader
     */
    private function getMockReader()
    {
        return $this->getMock('Doctrine\Common\Annotations\Reader');
    }
    
    /**
     * Gets the mock driver object.
     * 
     * @return Vich\GeographicalBundle\Driver\AnnotationDriver The driver
     */
    private function getDriver(Reader $reader)
    {
        return new AnnotationDriver($reader);
    }
    
    /**
     * Gets a mock geographical entity.
     * 
     * @return Vich\GeographicalBundle\Tests\DummyGeoEntity The entity
     */
    private function getMockGeographicalEntity()
    {
        return $this->getMock('Vich\GeographicalBundle\Tests\DummyGeoEntity');
    }
    
    /**
     * Gets a mock geographical annotation.
     * 
     * @return Vich\GeographicalBundle\Annotation\Geographical The annotation
     */
    private function getMockGeographicalAnnotation()
    {
        return $this->getMockBuilder('Vich\GeographicalBundle\Annotation\Geographical')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
    
    /**
     * Gets a mock geographical query annotation.
     * 
     * @return Vich\GeographicalBundle\Annotation\GeographicalQuery The annotation
     */
    private function getMockGeographicalQueryAnnotation()
    {
        return $this->getMockBuilder('Vich\GeographicalBundle\Annotation\GeographicalQuery')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}