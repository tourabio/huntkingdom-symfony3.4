<?php

namespace ForumBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;

/**
 * ReclamationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReclamationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findToHandle()
    {
        try {
            return $this->getEntityManager()
                ->createQuery(
                    "SELECT r
                FROM ForumBundle:Reclamation
                r where r.handled = 0 "
                )
                ->getResult();
        } catch (NonUniqueResultException $e) {
        }
    }
}
