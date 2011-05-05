<?php

namespace Vich\GeographicalBundle\QueryService;

/**
 * QueryServiceInterface.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
interface QueryServiceInterface
{
    /**
     * Query an address for coordinates.
     * 
     * @return QueryResult The result.
     */
    function queryForCoordinates($query);
}