<?php

namespace Vich\GeographicalBundle\EventListener;

use Vich\GeographicalBundle\Driver\AnnotationDriver;
use Vich\GeographicalBundle\QueryService\QueryServiceInterface;
use Vich\GeographicalBundle\Adapter\AdapterInterface;
use Vich\GeographicalBundle\Annotation\Geographical;
use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;

/**
 * GeographicalListener.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class GeographicalListener implements EventSubscriber
{
    /**
     * @var QueryServiceInterface $queryService
     */
    protected $queryService;
    
    /**
     * @var AnnotationDriver $reader
     */
    protected $driver;
    
    /**
     * @var AdapterInterface $adapter
     */
    protected $adapter;
    
    /**
     * Constructs a new instance of GeographicalListener.
     * 
     * @param QueryServiceInterface $queryService The query service.
     * @param AnnotationDriver $driver The driver.
     * @param AdapterInterface $adapter The database adapter.
     */
    function __construct(QueryServiceInterface $queryService, AnnotationDriver $driver, AdapterInterface $adapter)
    {
        $this->queryService = $queryService;
        $this->driver = $driver;
        $this->adapter = $adapter;
    }
    
    /**
     * The events the listener is subscribed to.
     * 
     * @return array An array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate'
        );
    }
    
    /**
     * Checks for persisted object to update coordinates
     *
     * @param EventArgs $args The event arguments
     */
    public function prePersist(EventArgs $args)
    {
        $obj = $this->adapter->getObjectFromArgs($args);
        
        $geo = $this->driver->readGeoAnnotation($obj);
        $geoQuery = $this->driver->readGeoQueryAnnotation($obj);
        
        $this->updateObject($obj, $geo, $geoQuery);
    }

    /**
     * Update coordinates on objects being updated before update
     * if they require changing
     *
     * @param EventArgs $args The event arguments
     */
    public function preUpdate(EventArgs $args)
    {
        $obj = $this->adapter->getObjectFromArgs($args);
        
        $geo = $this->driver->readGeoAnnotation($obj);
        if ($this->shouldQueryOnUpdate($geo)) {
            $geoQuery = $this->driver->readGeoQueryAnnotation($obj);
            
            $this->updateObject($obj, $geo, $geoQuery, true, $args);
        }
    }
    
    /**
     * Queries the service for coordinates.
     * 
     * @param object $obj The object.
     * @param Geographical $geo The greographical annotation.
     * @param GeographicalQuery $geoQuery The geogrphical query annotation.
     * @param boolean $isUpdate True if the object is being updated, false otherwise.
     * @param EventArgs $eventArgs The event args.
     */
    protected function updateObject($obj, $geo, $geoQuery, $isUpdate = false, EventArgs $eventArgs = null)
    {
        if (null === $geo) {
            return;
        }
        
        if (null === $geoQuery) {
            throw new \InvalidArgumentException('Unable to find GeograhicalQuery annotation.');
        }
        
        $queryMethod = $geoQuery->getMethod();
        $query = $obj->$queryMethod();
        
        $result = $this->queryService->queryCoordinates($query);
        
        $latSetter = sprintf('set%s', $geo->getLat());
        $lngSetter = sprintf('set%s', $geo->getLng());
        
        $obj->$latSetter($result->getLatitude());
        $obj->$lngSetter($result->getLongitude());
        
        if ($isUpdate) {
            $this->adapter->recomputeChangeSet($eventArgs);
        }
    }
    
    /**
     * Determines if the object coordinates should be updated when the object 
     * is udpated.
     * 
     * @param Geographical $annot The Geographical annotation.
     * @return boolean True if should query, false otherwise.
     */
    protected function shouldQueryOnUpdate(Geographical $annot = null)
    {
        return (null !== $annot) && ($annot->getOn() === Geographical::ON_UPDATE);
    }
}