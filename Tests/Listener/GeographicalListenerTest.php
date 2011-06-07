<?php

namespace Vich\GeographicalBundle\Tests\Listener;

use Vich\GeographicalBundle\Driver\AnnotationDriver;
use Vich\GeographicalBundle\QueryService\QueryServiceInterface;
use Vich\GeographicalBundle\Listener\GeographicalListener;
use Vich\GeographicalBundle\Tests\DummyGeoEntity;

/**
 * GeographicalListenerTest.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class GeographicalListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Vich\GeographicalBundle\Listener\GeographicalListener $listener
     */
    protected $listener;
    
    /**
     * @var Vich\GeographicalBundle\QueryService\QueryServiceInterface $queryService
     */
    protected $queryService;
    
    /**
     * @var Vich\GeographicalBundle\QueryService\QueryResult $queryResult
     */
    protected $queryResult;
    
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
    
    protected $lifecycleArgs;
    
    /**
     * Sets up the test.
     */
    protected function setUp()
    {
        $this->driver = $this->getMockDriver();
        $this->queryService = $this->getMockQueryService();
        $this->queryResult = $this->getMockQueryResult();
        $this->listener = $this->getListener($this->driver, $this->queryService);
        $this->geographicalEntity = $this->getGeographicalEntity();
        $this->geographicalAnnotation = $this->getMockGeographicalAnnotation();
        $this->geographicalQueryAnnotation = $this->getMockGeographicalQueryAnnotation();
        $this->lifecycleArgs = $this->getMockLifecycleArgs();
    }
    
    /**
     * Test the prePersist method of the GeographicalListener.
     */
    public function testPrePersist()
    {
        $this->lifecycleArgs
               ->expects($this->once())
               ->method('getEntity')
               ->will($this->returnValue($this->geographicalEntity));
        
        $this->geographicalAnnotation
               ->expects($this->once())
               ->method('getLat')
               ->will($this->returnValue('latitude'));
        
        $this->geographicalAnnotation
               ->expects($this->once())
               ->method('getLng')
               ->will($this->returnValue('longitude'));
        
        $this->geographicalQueryAnnotation
               ->expects($this->once())
               ->method('getMethod')
               ->will($this->returnValue('getAddress'));
        
        $this->queryService
               ->expects($this->once())
               ->method('queryForCoordinates')
               ->with($this->anything())
               ->will($this->returnValue($this->queryResult));
        
        $this->queryResult
               ->expects($this->once())
               ->method('getLatitude')
               ->will($this->returnValue(50));
        
        $this->queryResult
               ->expects($this->once())
               ->method('getLongitude')
               ->will($this->returnValue(-50));
        
        $this->driver
               ->expects($this->once())
               ->method('getGeographicalAnnotation')
               ->will($this->returnValue($this->geographicalAnnotation));
        
        $this->driver
               ->expects($this->once())
               ->method('getGeographicalQueryAnnotation')
               ->will($this->returnValue($this->geographicalQueryAnnotation));
        
        $this->listener->prePersist($this->lifecycleArgs);
        
        $this->assertEquals(50, $this->geographicalEntity->getLatitude());
        $this->assertEquals(-50, $this->geographicalEntity->getLongitude());
    }
    
    /**
     * Gets a mock query service.
     * 
     * @return Vich\GeographicalBundle\QueryService\QueryServiceInterface The query service
     */
    public function getMockQueryService()
    {
        return $this->getMock('Vich\GeographicalBundle\QueryService\QueryServiceInterface');
    }
    
    /**
     * Gets the mock driver object.
     * 
     * @return Vich\GeographicalBundle\Driver\AnnotationDriver The driver
     */
    private function getMockDriver()
    {
        return $this->getMockBuilder('Vich\GeographicalBundle\Driver\AnnotationDriver')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
    
    /**
     * Gets the mock listener.
     * 
     * @param Vich\GeographicalBundle\Driver\AnnotationDriver $driver The driver
     * @param Vich\GeographicalBundle\QueryService\QueryServiceInterface $queryService The query service
     * @return Vich\GeographicalBundle\Listener\GeographicalListener The listener
     */
    private function getListener(AnnotationDriver $driver, QueryServiceInterface $queryService)
    {
        $listener = new GeographicalListener($driver);
        $listener->setQueryService($queryService);
        
        return $listener;
    }
    
    /**
     * Gets a mock query result.
     * 
     * @return Vich\GeographicalBundle\QueryService\QueryResult The query result
     */
    private function getMockQueryResult()
    {
        return $this->getMock('Vich\GeographicalBundle\QueryService\QueryResult');
    }
    
    /**
     * Gets a mock query service.
     * 
     * @return Vich\GeographicalBundle\Tests\DummyGeoEntity The entity
     */
    private function getGeographicalEntity()
    {
        return new DummyGeoEntity();
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
    
    /**
     * Gets the mock lifecycle event args.
     * 
     * @return Doctrine\ORM\Event\LifecycleEventArgs The event args 
     */
    private function getMockLifecycleArgs()
    {
        return $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}