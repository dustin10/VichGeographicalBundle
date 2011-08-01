<?php

namespace Vich\GeographicalBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Vich\GeographicalBundle\Driver\AnnotationDriver;
use Vich\GeographicalBundle\QueryService\QueryServiceInterface;

/**
 * GeographicalListenerInterface.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
interface GeographicalListenerInterface extends EventSubscriber
{
    /**
     * Sets the annotation driver.
     * 
     * @param AnnotationDriver $driver The annotation driver
     */
    function setAnnotationDriver(AnnotationDriver $driver);
    
    /**
     * Sets the query service.
     * 
     * @param QueryServiceInterface $queryService The query service
     */
    function setQueryService(QueryServiceInterface $queryService);
}