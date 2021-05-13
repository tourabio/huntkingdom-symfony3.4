<?php

namespace TrainingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Animal
 *
 * @ORM\Table(name="animal")
 * @ORM\Entity(repositoryClass="TrainingBundle\Repository\AnimalRepository")
 */
class Animal
{
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
     * @ORM\Column(name="debutSaison", type="integer")
     */
    private $debutSaison;


    /**
     * @var int
     *
     * @ORM\Column(name="finSaison", type="integer")
     */
    private $finSaison;

    /**
     * @return int
     */
    public function getDebutSaison()
    {
        return $this->debutSaison;
    }

    /**
     * @param int $debutSaison
     */
    public function setDebutSaison($debutSaison)
    {
        $this->debutSaison = $debutSaison;
    }

    /**
     * @return int
     */
    public function getFinSaison()
    {
        return $this->finSaison;
    }

    /**
     * @param int $finSaison
     */
    public function setFinSaison($finSaison)
    {
        $this->finSaison = $finSaison;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank(message="lieu ne doit pas etre vide")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image_animal", type="string", length=255)
     */
    private $image_animal;




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
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Animal
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Animal
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Animal
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

    /**
     * @return string
     */
    public function getImageAnimal()
    {
        return $this->image_animal;
    }

    /**
     * @param string $image_animal
     */
    public function setImageAnimal($image_animal)
    {
        $this->image_animal = $image_animal;
    }







}