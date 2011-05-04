<?php

namespace Vich\GeographicalBundle\Listener;

use Doctrine\Common\EventSubscriber;
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
    
    public function getSubscribedEvents()
    {
        return array();
    }
}