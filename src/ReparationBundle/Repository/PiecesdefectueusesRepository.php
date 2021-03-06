<?php

namespace ReparationBundle\Repository;

/**
 * PiecesdefectueusesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PiecesdefectueusesRepository extends \Doctrine\ORM\EntityRepository
{

    public function findDefective(){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM ReparationBundle:Piecesdefectueuses p
                WHERE p.reserved =:yep'
            )
            ->setParameter('yep', false)
            ->getResult();


    }
    public function find_my_Defective($idUser){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM ReparationBundle:Piecesdefectueuses p
                WHERE p.reserved =:yep AND p.user=:id'
            )
            ->setParameter('yep', true)
            ->setParameter('id', $idUser)
            ->getResult();
    }
    public function count_myReady($idUser){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT count(p.id)
                FROM ReparationBundle:Piecesdefectueuses p
                WHERE p.etat =:yep AND p.user=:id'
            )
            ->setParameter('yep', true)
            ->setParameter('id', $idUser)
            ->getSingleScalarResult();
    }

    public function search_by_name($name){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM ReparationBundle:Piecesdefectueuses p
                WHERE p.nom LIKE :str'
            )
            ->setParameter('str', '%'.$name.'%')
            ->getResult();
    }
}
