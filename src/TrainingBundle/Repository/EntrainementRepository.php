<?php

namespace TrainingBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * EntrainementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntrainementRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEntr($user)
    {
        $Query=$this->getEntityManager()->createQuery(
            "select E from TrainingBundle:Entrainement E where E.user = :userid ")
            ->setParameter('userid',$user);


        return $Query->getResult();
    }
    public function findEntrainementNUll()
    {
        $Query=$this->getEntityManager()->createQuery(
            "select E from TrainingBundle:Entrainement E where E.accepter = :accepter ")
            ->setParameter('accepter','encours');


        return $Query->getResult();
    }
    public function findEntrainementOui()
    {
        $Query=$this->getEntityManager()->createQuery(
            "select E from TrainingBundle:Entrainement E where E.accepter = :accepter ")
            ->setParameter('accepter','oui');


        return $Query->getResult();
    }

    public function findEntrDate()
    {
        $today=new \DateTime();
        $Query=$this->getEntityManager()->createQuery(
            "select E from TrainingBundle:Entrainement E where E.dateEnt < :today ")
            ->setParameter('today',$today);

        return $Query->getResult();
    }
    public function getTraining()
    {
        $Query=$this->getEntityManager()->createQuery(
            "select E,A from TrainingBundle:Entrainement E ,TrainingBundle:Animal A where E.user = :userid and A.id=E.animal ")
            ->setParameter('userid',5);


        return $Query->getResult();
    }
    function StatMobile1()
    {
        try {
            return $this->getEntityManager()->createQuery(
                "Select SUM(E.id)*100 as pourcentage from TrainingBundle:Entrainement E  Where E.categorie=:cat"
            )->setParameter('cat', "Hunting")->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }
    function StatMobile2()
    {
        try {
            return $this->getEntityManager()->createQuery(
                "Select SUM(E.id)*100 as pourcentage from TrainingBundle:Entrainement E  Where E.categorie=:cat"
            )->setParameter('cat', "Fishing")->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }
    function TotTraining()
    {
        try {
            return $this->getEntityManager()->createQuery(
                "select SUM(E.id) as total from TrainingBundle:Entrainement E"
            )->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }


}