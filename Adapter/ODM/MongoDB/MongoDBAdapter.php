<?php

namespace Vich\GeographicalBundle\Adapter\ODM\MongoDB;

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
    public function recomputeChangeSet(EventArgs $e)
    {
        $obj = $this->getObjectFromArgs($e);
        
        $dm = $e->getDocumentManager();
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
