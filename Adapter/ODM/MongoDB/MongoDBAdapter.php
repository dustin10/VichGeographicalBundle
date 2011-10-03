<?php

namespace Vich\GeographicalBundle\Adapter\ORM;

use Vich\GeographicalBundle\Adapter\AdapterInterface;
use Doctrine\Common\EventArgs;
use Doctrine\ODM\MongoDB\Proxy\Proxy;

/**
 * MongoDBAdapter.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class MongoDBAdapter implements AdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function getObjectFromArgs(EventArgs $e)
    {
        return $e->getDocument();
    }
    
    /**
     * {@inheritDoc}
     */
    public function invokeChangesetRecompute(EventArgs $e)
    {
        $obj = $this->getObjectFromArgs($e);
        
        $dm = $args->getDocumentManager();
        $uow = $dm->getUnitOfWork();
        $metadata = $dm->getClassMetadata(get_class($obj));
        $uow->recomputeSingleDocumentChangeSet($metadata, $obj);
    }
    
    /**
     * {@inheritDoc}
     */
    public function isProxy($obj)
    {
        return $obj instanceof Proxy;
    }
}