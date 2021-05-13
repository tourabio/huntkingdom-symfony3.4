<?php

namespace ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="ServiceBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Hebergement")
     * @ORM\JoinColumn(name="HebergementId", referencedColumnName="id")
     */
    private $Hebergement;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="nbJours", type="integer")
     */
    private $nbJours;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateArrivee", type="date")
     */
    private $dateArrivee;

    /**
     * @var float
     *
     * @ORM\Column(name="prixTot", type="float")
     */
    private $prixTot;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nbJours
     *
     * @param integer $nbJours
     *
     * @return Location
     */
    public function setNbJours($nbJours)
    {
        $this->nbJours = $nbJours;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getHebergement()
    {
        return $this->Hebergement;
    }

    /**
     * @param mixed $Hebergement
     */
    public function setHebergement($Hebergement)
    {
        $this->Hebergement = $Hebergement;
    }

    /**
     * Get nbJours
     *
     * @return int
     */
    public function getNbJours()
    {
        return $this->nbJours;
    }

    /**
     * Set dateArrivee
     *
     * @param \DateTime $dateArrivee
     *
     * @return Location
     */
    public function setDateArrivee($dateArrivee)
    {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    /**
     * Get dateArrivee
     *
     * @return \DateTime
     */
    public function getDateArrivee()
    {
        return $this->dateArrivee;
    }

    /**
     * Set prixTot
     *
     * @param float $prixTot
     *
     * @return Location
     */
    public function setPrixTot($prixTot)
    {
        $this->prixTot = $prixTot;

        return $this;
    }

    /**
     * Get prixTot
     *
     * @return float
     */
    public function getPrixTot()
    {
        return $this->prixTot;
    }
}

