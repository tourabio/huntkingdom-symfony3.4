<?php

namespace ReparationBundle\Repository;

/**
 * ReparationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReparationRepository extends \Doctrine\ORM\EntityRepository
{
    public function find_my_reparation($idP){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT r
                FROM ReparationBundle:Reparation r
                WHERE r.piecedefectueuse =:id '
            )
            ->setParameter('id', $idP)
            ->getSingleResult();
    }

    public function find_my_ready($idUser,$date){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT r
                FROM ReparationBundle:Reparation r
                INNER JOIN ReparationBundle:Piecesdefectueuses p
                WHERE r.piecedefectueuse=p.id AND p.user=:id AND r.dateFin<=:current'
            )
            ->setParameter('id', $idUser)
            ->setParameter('current', $date)
            ->getResult();
    }


}
