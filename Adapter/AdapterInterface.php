<?php

namespace Vich\GeographicalBundle\Adapter;

use Doctrine\Common\EventArgs;

/**
 * AdapterInterface.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
interface AdapterInterface
{
    /**
     * Gets the mapped object from the event arguments.
     * 
     * @param EventArgs $e The event arguments.
     * @return object The mapped object.
     */
    function getObjectFromArgs(EventArgs $e);
    
    /**
     * Notifies the ORM layer that a recompute of the entities changeset is necessary.
     * 
     * @param EventArgs $e The event arguments.
     */
    function invokeChangesetRecompute(EventArgs $e);
    
    /**
     * Determines if the specified object is a proxy.
     * 
     * @param mixed $obj The object to test.
     * @return boolean True if a proxy object, false otherwise.
     */
    function isProxy($obj);
}