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
     */
    function queryForCoordinates($query);
}