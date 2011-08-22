<?php

namespace Vich\GeographicalBundle\Listener\ORM;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
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
        $obj = $args->getEntity();
        
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
        $obj = $args->getEntity();
        
        $geographical = $this->driver->getGeographicalAnnotation($obj);
        if (null !== $geographical && $geographical->getOn() === Geographical::ON_UPDATE) {
            $geographicalQuery = $this->driver->getGeographicalQueryAnnotation($obj);
            if (null !== $geographicalQuery) {
                $this->updateEntity($obj, $geographical, $geographicalQuery, $args);

                $em = $args->getEntityManager();
                $uow = $em->getUnitOfWork();
                $metadata = $em->getClassMetadata(get_class($obj));
                $uow->recomputeSingleEntityChangeSet($metadata, $obj);
            }
        }
    }
}
