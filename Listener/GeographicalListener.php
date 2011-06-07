<?php

namespace Vich\GeographicalBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Vich\GeographicalBundle\Driver\AnnotationDriver;
use Vich\GeographicalBundle\QueryService\QueryServiceInterface;
use Vich\GeographicalBundle\Annotation\Geographical;

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
    private $queryService;
    
    /**
     * @var AnnotationDriver $reader
     */
    private $driver;
    
    /**
     * Sets the query service the listener should use to query for coordinates.
     * 
     * @param QueryServiceInterface $queryService The query service
     */
    public function setQueryService(QueryServiceInterface $queryService)
    {
        $this->queryService = $queryService;
    }
    
    /**
     * Constructs a new instance of GeographicalListener.
     * 
     * @param Reader $reader The annotation reader
     */
    public function __construct(AnnotationDriver $driver)
    {
        $this->driver = $driver;
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
     * @param LifecycleEventArgs $args The event arguments
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $obj = $args->getEntity();
        $reflClass = new \ReflectionClass($obj);
        
        $geographical = $this->driver->getGeographicalAnnotation($obj);

        if ($geographical) {
            $geographicalQuery = $this->driver->getGeographicalQueryAnnotation($obj);
            if ($geographicalQuery) {
                $this->queryCoordinates($obj, $geographical, $geographicalQuery);
            }
        }
        
    }

    /**
     * Update coordinates on objects being updated before update
     * if they require changing
     *
     * @param PreUpdateEventArgs $args The event arguments
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $obj = $args->getEntity();
        
        $geographical = $this->driver->getGeographicalAnnotation($obj);
        if ($geographical && $geographical->getOn() === Geographical::ON_UPDATE) {
            $geographicalQuery = $this->driver->getGeographicalQueryAnnotation($obj);
            if ($geographicalQuery) {
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
    private function queryCoordinates($entity, $geographical, $geographicalQuery)
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