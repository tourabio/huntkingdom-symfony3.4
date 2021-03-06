<?php

namespace ForumBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;

/**
 * PublicationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PublicationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findPinnedFirst()
    {
        try {
            return $this->getEntityManager()
                ->createQuery(
                    "SELECT p
                FROM ForumBundle:Publication
                p ORDER BY p.pinned DESC "
                )
                ->getResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM ForumBundle:Publication p
                WHERE p.title LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }

}
