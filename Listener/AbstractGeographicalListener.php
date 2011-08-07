<?php

namespace Vich\GeographicalBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Vich\GeographicalBundle\Driver\AnnotationDriver;
use Vich\GeographicalBundle\QueryService\QueryServiceInterface;
use Vich\GeographicalBundle\Annotation\Geographical;

/**
 * AbstractGeographicalListener.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
abstract class AbstractGeographicalListener implements GeographicalListenerInterface
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
     * {@inheritDoc}
     */
    public function setAnnotationDriver(AnnotationDriver $driver)
    {
        $this->driver = $driver;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setQueryService(QueryServiceInterface $queryService)
    {
        $this->queryService = $queryService;
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
     * Updates the object on pre persist.
     * 
     * @param object $obj The object
     */
    protected function doPrePersist($obj)
    {
        $geographical = $this->driver->getGeographicalAnnotation($obj);
        if ($geographical) {
            $geographicalQuery = $this->driver->getGeographicalQueryAnnotation($obj);
            if (null !== $geographicalQuery) {
                $this->queryCoordinates($obj, $geographical, $geographicalQuery);
            }
        }
    }
    
    /**
     * Updates the object on pre update.
     * 
     * @param object $obj The object
     */
    protected function doPreUpdate($obj)
    {
        $geographical = $this->driver->getGeographicalAnnotation($obj);
        if (null !== $geographical && $geographical->getOn() === Geographical::ON_UPDATE) {
            $geographicalQuery = $this->driver->getGeographicalQueryAnnotation($obj);
            if (null !== $geographicalQuery) {
                $this->queryCoordinates($obj, $geographical, $geographicalQuery);
            }
        }
    }
    
    /**
     * Queries the service for coordinates.
     * 
     * @param Object $entity The entity
     * @param Geographical $geographical The greographical annotation
     * @param GeographicalQuery $geographicalQuery The geogrphical query annotation
     */
    protected function queryCoordinates($entity, $geographical, $geographicalQuery)
    {
        $queryMethod = $geographicalQuery->getMethod();
        $query = $entity->$queryMethod();
        
        $result = $this->queryService->queryForCoordinates($query);
        
        $latSetter = 'set'.$geographical->getLat();
        $lngSetter = 'set'.$geographical->getLng();
        
        $entity->$latSetter($result->getLatitude());
        $entity->$lngSetter($result->getLongitude());
    }
}