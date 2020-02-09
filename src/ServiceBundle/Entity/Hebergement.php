<?php

namespace ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hebergement
 *
 * @ORM\Table(name="hebergement")
 * @ORM\Entity(repositoryClass="ServiceBundle\Repository\HebergementRepository")
 */
class Hebergement extends Service
{
    /**
     * @ORM\ManyToOne(targetEntity="Service")
     * @ORM\JoinColumn(name="serviceId", referencedColumnName="id")
     */
    private $service;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="nbChambre", type="integer")
     */
    private $nbChambre;

    /**
     * @var int
     *
     * @ORM\Column(name="nbLits", type="integer")
     */
    private $nbLits;

    /**
     * @var int
     *
     * @ORM\Column(name="capacite", type="integer")
     */
    private $capacite;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;


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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Hebergement
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set nbChambre
     *
     * @param integer $nbChambre
     *
     * @return Hebergement
     */
    public function setNbChambre($nbChambre)
    {
        $this->nbChambre = $nbChambre;

        return $this;
    }

    /**
     * Get nbChambre
     *
     * @return int
     */
    public function getNbChambre()
    {
        return $this->nbChambre;
    }

    /**
     * Set nbLits
     *
     * @param integer $nbLits
     *
     * @return Hebergement
     */
    public function setNbLits($nbLits)
    {
        $this->nbLits = $nbLits;

        return $this;
    }

    /**
     * Get nbLits
     *
     * @return int
     */
    public function getNbLits()
    {
        return $this->nbLits;
    }

    /**
     * Set capacite
     *
     * @param integer $capacite
     *
     * @return Hebergement
     */
    public function setCapacite($capacite)
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * Get capacite
     *
     * @return int
     */
    public function getCapacite()
    {
        return $this->capacite;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Hebergement
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}

