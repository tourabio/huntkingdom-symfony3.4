<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use TrainingBundle\Entity\Animal;
use TrainingBundle\Entity\Entrainement;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class TrainingMobileController extends Controller
{

    public function allTrainingUserAction($id)
    {

        $entrainement = $this->getDoctrine()->getManager()
            ->getRepository('TrainingBundle:Entrainement')->findBy(array(
                'user'=>$id));
        foreach ($entrainement as $e) {
            $e->setDateEnt($e->getDateEnt()->format('Y-m-d'));
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($entrainement);
        return new JsonResponse($formatted);
    }
    public function allTrainingAction()
    {

        $entrainement = $this->getDoctrine()->getManager()
            ->getRepository('TrainingBundle:Entrainement')->findEntrainementNUll();
        foreach ($entrainement as $e) {
            $e->setDateEnt($e->getDateEnt()->format('Y-m-d'));
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($entrainement);
        return new JsonResponse($formatted);
    }
    public function AnimalsAction()
    {
        $animals = $this->getDoctrine()->getManager()
            ->getRepository('TrainingBundle:Animal')->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animals);
        return new JsonResponse($formatted);
    }
    public function AnimalsHuntingAction()
    {
        $animals = $this->getDoctrine()->getManager()
            ->getRepository('TrainingBundle:Animal')->findAnimalHunting();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animals);
        return new JsonResponse($formatted);
    }
    public function AnimalsFishingAction()
    {
        $animals = $this->getDoctrine()->getManager()
            ->getRepository('TrainingBundle:Animal')->findAnimalFishing();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animals);
        return new JsonResponse($formatted);
    }
    public function UserConnectedAction($name)
    {
        $animals = $this->getDoctrine()->getManager()
            ->getRepository('UserBundle:User')->findBy(array(
                'username'=>$name));

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animals);
        return new JsonResponse($formatted);
    }
    public function ProductsAction()
    {
        $produits = $this->getDoctrine()->getManager()->getRepository('ProductBundle:Produit')->findAll();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produits);
        return new JsonResponse($formatted);
    }
    public function AnimalsTrainingAction($nom)
    {
        $animals = $this->getDoctrine()->getManager()
            ->getRepository('TrainingBundle:Animal')->findIdAnimal($nom);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animals);
        return new JsonResponse($formatted);
    }
    public function AnimalsTrainingIdAction($id)
    {
        $animals = $this->getDoctrine()->getManager()
            ->getRepository('TrainingBundle:Animal')->findAnimalId($id);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animals);
        return new JsonResponse($formatted);
    }
    public function ProductsTrainingAction($libProd)
    {
        $animals = $this->getDoctrine()->getManager()
            ->getRepository('ProductBundle:Produit')->findIdProduct($libProd);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animals);
        return new JsonResponse($formatted);
    }
    public function ProductsTrainingIdAction($id)
    {
        $animals = $this->getDoctrine()->getManager()
            ->getRepository('ProductBundle:Produit')->findProductId($id);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animals);
        return new JsonResponse($formatted);
    }

    public function AddTrainingAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entrainement= new Entrainement();
        $entrainement->setCategorie($request->get('categorie'));
        $entrainement->setNbHeures($request->get('nbHeures'));

        try {
            $entrainement->setDateEnt(new \DateTime($request->get('dateEnt')));
        } catch (\Exception $e) {
        }
        $entrainement->setPrix($request->get('prix'));
        $entrainement->setLieu($request->get('lieu'));
        $user = $em->getRepository('UserBundle:User')->find($request->get('userId'));
        $animal = $em->getRepository('TrainingBundle:Animal')->find($request->get('animalId'));
        $produit = $em->getRepository('ProductBundle:Produit')->find($request->get('produitId'));
        $entrainement->setUser($user);
        $entrainement->setAnimal($animal);
        $entrainement->setProduit($produit);
        $entrainement->setAccepter($request->get('accepter'));

        $em->persist($entrainement);
        $em->flush();



        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($entrainement);

        return new JsonResponse($formatted);
    }

    public function AddAnimalAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $animal= new Animal();
        $animal->setCategorie($request->get('categorie'));
        $animal->setNom($request->get('nom'));
        $animal->setDescription($request->get('description'));
        $animal->setImageAnimal($request->get('image_animal'));
        $animal->setDebutSaison($request->get('debutSaison'));
        $animal->setFinSaison($request->get('finSaison'));

        $em->persist($animal);
        $em->flush();

        //$entrainement->setDateEnt($entrainement->getDateEnt()->format('Y-m-d'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animal);

        return new JsonResponse($formatted);
    }

    public function DeleteTrainingAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entrainement = $this->getDoctrine()->getManager()
            ->getRepository('TrainingBundle:Entrainement')
            ->find($id);
        $em->remove($entrainement);
        $em->flush();
        //$entrainement->setDateCreation($boutique->getDateCreation()->format('Y-m-d H:i:s'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($entrainement);
        return new JsonResponse($formatted);
    }

    public function AddImageAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        $animal = $em->getRepository('TrainingBundle:Animal')->find($id);

        if ($animal->getImageAnimal() !== null){
            $file = $animal->getImageAnimal();
            $filename = md5(uniqid()). '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $filename);
            $animal->setImageAnimal($filename);
        }
        else{
            $animal->setImageAnimal(' ');
        }

        $em->persist($animal);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animal);

        return new JsonResponse($formatted);
    }
    public function ModifyAnimalAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $animal= new Animal();
        $animal=$em->getRepository("TrainingBundle:Animal")->find($request->get('id'));
        $animal->setCategorie($request->get('categorie'));
        $animal->setNom($request->get('nom'));
        $animal->setDescription($request->get('description'));
        $animal->setDebutSaison($request->get('debutSaison'));
        $animal->setFinSaison($request->get('finSaison'));
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($animal);

        return new JsonResponse($formatted);
    }
    public function StatistiqueAction()
    {
        $tot= $this->getDoctrine()->getManager()->getRepository('TrainingBundle:Entrainement')
            ->TotTraining();
        $pourcentage1 = $this->getDoctrine()->getManager()->getRepository('TrainingBundle:Entrainement')
            ->StatMobile1($tot);
        $pourcentage2 = $this->getDoctrine()->getManager()->getRepository('TrainingBundle:Entrainement')
            ->StatMobile2($tot);
        $resp = array();
        $resp[0]=$pourcentage1;
        $resp[1]=$pourcentage2;
        $resp[2]=$tot;


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($resp);
        return new JsonResponse($formatted);
    }
    public function recupererEntraineurAction($id,Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $entrainement=$em->getRepository("TrainingBundle:Entrainement")->find($id);
        $user = $em->getRepository('UserBundle:User')->find($request->get('entraineur'));
        $entrainement->setEntraineur($user);
        $entrainement->setAccepter("oui");

        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($entrainement);
        return new JsonResponse($formatted);


    }




}
