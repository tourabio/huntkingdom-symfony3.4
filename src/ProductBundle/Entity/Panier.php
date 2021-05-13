<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\PanierRepository")
 */
class Panier
{
    /**
     * Many Groups have Many Users.
     * @ORM\ManyToMany(targetEntity="Produit", mappedBy="paniers",cascade={"persist"})
     */
    private $produits;

    public function __construct() {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function addToPanier($produit) {
        $this->produits[] = $produit;

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
     * @var float
     *
     * @ORM\Column(name="prixTotal", type="float")
     */
    private $prixTotal;

    /**
     * @var int
     *
     * @ORM\Column(name="nbArticles", type="integer")
     */
    private $nbArticles;



    /**
     * @return float
     */
    public function getPrixTotal()
    {
        return $this->prixTotal;
    }

    /**
     * @param float $prixTotal
     */
    public function setPrixTotal($prixTotal)
    {
        $this->prixTotal = $prixTotal;
    }

    /**
     * @return int
     */
    public function getNbArticles()
    {
        return $this->nbArticles;
    }

    /**
     * @param int $nbArticles
     */
    public function setNbArticles($nbArticles)
    {
        $this->nbArticles = $nbArticles;
    }

    /**
     * @return string
     */

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
     * @return mixed
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * @param mixed $produits
     */
    public function setProduits($produits)
    {
        $this->produits = $produits;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePanier", type="datetime")
     */
    private $datePanier;

    /**
     * @return \DateTime
     */
    public function getDatePanier()
    {
        return $this->datePanier;
    }

    /**
     * @param \DateTime $datePanier
     */
    public function setDatePanier($datePanier)
    {
        $this->datePanier = $datePanier;
    }




}
