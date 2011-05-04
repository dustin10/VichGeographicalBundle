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
    function setQueryService(QueryServiceInterface $queryService);
}