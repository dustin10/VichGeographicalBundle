<?php

namespace Vich\GeographicalBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Vich\GeographicalBundle\Listener\GeographicalListenerInterface;
use Vich\GeographicalBundle\QueryService\QueryServiceInterface;

/**
 * GeographicalListener.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class GeographicalListener implements EventSubscriber, GeographicalListenerInterface
{
    /**
     * @var QueryServiceInterface $queryService
     */
    private $queryService;
    
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
     * The events the listener is subscribed to.
     * 
     * @return array An array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'onFlush',
            'loadClassMetadata'
        );
    }
    
    /**
     * Maps additional metadata.
     * 
     * @param EventArgs $args The event arguments
     */
    public function loadClassMetadata(EventArgs $args)
    {
        
    }

    /**
     * Checks for persisted object to update coordinates
     *
     * @param EventArgs $args The event arguments
     */
    public function prePersist(EventArgs $args)
    {
        
    }

    /**
     * Update coordinates on objects being updated during flush
     * if they require changing
     *
     * @param EventArgs $args The event arguments
     */
    public function onFlush(EventArgs $args)
    {
        
    }
}