<?php

namespace ServiceBundle\Repository;

/**
 * LocationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LocationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findLocations($id){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT l
                FROM ServiceBundle:Location l
                WHERE l.MoyenDeTransport =:id AND (l.dateArrivee + l.nbJours)> CURRENT_DATE() ORDER BY l.dateArrivee'
            )
            ->setParameter('id', $id)
            ->getResult();
    }
}
