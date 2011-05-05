<?php

namespace Vich\GeographicalBundle\Listener;

use Vich\GeographicalBundle\QueryService\QueryServiceInterface;

/**
 * GeographicalListenerInterface.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
interface GeographicalListenerInterface
{
    /**
     * Sets the query service.
     * 
     * @param QueryServiceInterface $queryService The query service
     */
    function setQueryService(QueryServiceInterface $queryService);
}