<?php

namespace Vich\GeographicalBundle\Listener\ODM\MongoDB;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use Vich\GeographicalBundle\Listener\AbstractGeographicalListener;
use Vich\GeographicalBundle\Annotation\Geographical;

/**
 * GeographicalListener.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class GeographicalListener extends AbstractGeographicalListener
{
    /**
     * Checks for persisted object to update coordinates
     *
     * @param LifecycleEventArgs $args The event arguments
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $obj = $args->getDocument();
        
        $geographical = $this->driver->getGeographicalAnnotation($obj);
        if ($geographical) {
            $geographicalQuery = $this->driver->getGeographicalQueryAnnotation($obj);
            if (null !== $geographicalQuery) {
                $this->updateEntity($obj, $geographical, $geographicalQuery);
            }
        }
    }

    /**
     * Update coordinates on objects being updated before update
     * if they require changing
     *
     * @param PreUpdateEventArgs $args The event arguments
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $obj = $args->getDocument();
        
        $geographical = $this->driver->getGeographicalAnnotation($obj);
        if (null !== $geographical && $geographical->getOn() === Geographical::ON_UPDATE) {
            $geographicalQuery = $this->driver->getGeographicalQueryAnnotation($obj);
            if (null !== $geographicalQuery) {
                $this->updateEntity($obj, $geographical, $geographicalQuery, $args);

                $dm = $args->getDocumentManager();
                $uow = $dm->getUnitOfWork();
                $metadata = $dm->getClassMetadata(get_class($obj));
                $uow->recomputeSingleDocumentChangeSet($metadata, $obj);
            }
        }
    }
}
