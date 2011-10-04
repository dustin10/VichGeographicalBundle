<?php

namespace Vich\GeographicalBundle\Adapter\ORM;

use Vich\GeographicalBundle\Adapter\AdapterInterface;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Proxy\Proxy;

/**
 * DoctrineORMAdapter.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class DoctrineORMAdapter implements AdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function getObjectFromArgs(EventArgs $e)
    {
        return $e->getEntity();
    }
    
    /**
     * {@inheritDoc}
     */
    public function recomputeChangeSet(EventArgs $e)
    {
        $obj = $this->getObjectFromArgs($e);
        
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $metadata = $em->getClassMetadata(get_class($obj));
        $uow->recomputeSingleEntityChangeSet($metadata, $obj);
    }
    
    /**
     * {@inheritDoc}
     */
    public function isProxy($obj)
    {
        return $obj instanceof Proxy;
    }
}