<?php

namespace ApiBundle\Controller;

use ServiceBundle\Entity\Hebergement;
use ServiceBundle\Entity\Location;
use ServiceBundle\Entity\MoyenDeTransport;
use ServiceBundle\Entity\Reservation;
use ServiceBundle\Form\LocationType;
use ServiceBundle\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ServiceMobileController extends Controller
{
    public function afficherhebergementfrontAction()
    {
        $em = $this->getDoctrine()->getManager();
        $hebergements= $em->getRepository("ServiceBundle:Hebergement")->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($hebergements);
        return new JsonResponse($formatted);
    }

    public function addHebergementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Hebergement = new Hebergement();
        $Hebergement->setType($request->get('type'));
        $Hebergement->setDescription($request->get('description'));
        $Hebergement->setImage($request->get('image'));
        $Hebergement->setPrixParJour($request->get('prixParJour'));
        $Hebergement->setNbChambre($request->get('nbChambre'));
        $Hebergement->setNbLits($request->get('nblits'));
        $Hebergement->setCapacite($request->get('capacite'));
        $Hebergement->setAdresse($request->get('adresse'));
        $em->persist($Hebergement);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Hebergement);
        return new JsonResponse($formatted);
    }
    public function affichertransportfrontAction()
    {
        $em = $this->getDoctrine()->getManager();
        $transports= $em->getRepository("ServiceBundle:MoyenDeTransport")->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($transports);
        return new JsonResponse($formatted);
    }
    public function addTransportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Transport=new MoyenDeTransport();
        $Transport->setType($request->get('type'));
        $Transport->setCategorie($request->get('categorie'));
        $Transport->setImage($request->get('image'));
        $Transport->setPrixParJour($request->get('prixParJour'));
        $Transport->setMarque($request->get('marque'));
        $em->persist($Transport);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Transport);
        return new JsonResponse($formatted);
    }
//    public function AddImageAction(Request $request,$id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $Transport = $em->getRepository('ServiceBundle:MoyenDeTransport')->find($id);
//        if ($Transport->getImage() !== null){
//            $file = $Transport->getImage();
//            $filename = md5(uniqid()). '.' . $file->guessExtension();
//            $file->move($this->getParameter('media_directory'), $filename);
//            $Transport->setImage($filename);
//        }
//        else{
//            $Transport->setImage(' ');
//        }
//        $em->persist($Transport);
//        $em->flush();
//        $serializer = new Serializer([new ObjectNormalizer()]);
//        $formatted = $serializer->normalize($Transport);
//        return new JsonResponse($formatted);
//    }
    public function supprimertransportAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $Transport=$em->getRepository("ServiceBundle:MoyenDeTransport")->find($id);
        $em->remove($Transport);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Transport);
        return new JsonResponse($formatted);
    }
    public function modifiertransportAction(Request $request)
    {
        $Transport = new MoyenDeTransport();
        $em=$this->getDoctrine()->getManager();
        $Transport=$em->getRepository("ServiceBundle:MoyenDeTransport")->find($request->get('id'));
        //$Transport->setId($request->get('id'));
        $Transport->setType($request->get('type'));
        $Transport->setCategorie($request->get('categorie'));
        $Transport->setImage($request->get('image'));
        $Transport->setPrixParJour($request->get('prixParJour'));
        $Transport->setMarque($request->get('marque'));
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Transport);
        return new JsonResponse($formatted);
    }
    public function afficheruntransportfrontAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $transports= $em->getRepository("ServiceBundle:MoyenDeTransport")->find($request->get('id'));
        $locations= $em->getRepository("ServiceBundle:Location")->findLocations($transports);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($locations);
        return new JsonResponse($formatted);
    }
    public function afficherunhebergementfrontAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $hebergements= $em->getRepository("ServiceBundle:Hebergement")->find($id);
        $reservations= $em->getRepository("ServiceBundle:Reservation")->findReservations($hebergements);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reservations);
        return new JsonResponse($formatted);
    }
    public function afficherMyfrontAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $locations= $em->getRepository("ServiceBundle:Location")->findBy(array('user' => $id));
        $reservations= $em->getRepository("ServiceBundle:Reservation")->findBy(array('user' => $id));
        $array_merged = [
            "reservations" => $reservations,
            "locations"=>$locations
        ];
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($array_merged);
        return new JsonResponse($formatted);
    }
    public function afficherMyReservationsfrontAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user= $em->getRepository("UserBundle:User")->find($request->get('id'));
        $reservations= $em->getRepository("ServiceBundle:Reservation")->findBy(array('user' => $user));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reservations);
        return new JsonResponse($formatted);
    }
    public function afficherMyLocationsfrontAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user= $em->getRepository("UserBundle:User")->find($request->get('id'));
        $reservations= $em->getRepository("ServiceBundle:Location")->findBy(array('user' => $user));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reservations);
        return new JsonResponse($formatted);
    }
    public function ajouterLocationAction(Request $request){
        $Location=new Location();
        $em = $this->getDoctrine()->getManager();
        $Transport= $em->getRepository("ServiceBundle:MoyenDeTransport")->find($request->get('idm'));
            //$reservation->setPrixTot($hebergements->getPrixParJour()*$reservation->getNbJours());

        $user= $em->getRepository("UserBundle:User")->find($request->get('idu'));
        $Location->setUser($user);
        $Location->setMoyenDeTransport($Transport);
        $Location->setNbJours($request->get('nbjours'));
        $Location->setPrixTot($request->get('prixfinal'));
        try {
            $Location->setDateArrivee(new \DateTime($request->get('datearrivee')));
        } catch (\Exception $e) {
        }

        $em=$this->getDoctrine()->getManager();
            $em->persist($Location);
            $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Location);
        return new JsonResponse($formatted);
    }
    public function ajouterReservationAction(Request $request){
        $Reservation=new Reservation();
        $em = $this->getDoctrine()->getManager();
        $Hebergement= $em->getRepository("ServiceBundle:Hebergement")->find($request->get('idh'));
        //$reservation->setPrixTot($hebergements->getPrixParJour()*$reservation->getNbJours());

        $user= $em->getRepository("UserBundle:User")->find($request->get('idu'));
        $Reservation->setUser($user);
        $Reservation->setHebergement($Hebergement);
        $Reservation->setNbJours($request->get('nbjours'));
        $Reservation->setPrixTot($request->get('prixfinal'));
        try {
            $Reservation->setDateArrivee(new \DateTime($request->get('datearrivee')));
        } catch (\Exception $e) {
        }

        $em=$this->getDoctrine()->getManager();
        $em->persist($Reservation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Reservation);
        return new JsonResponse($formatted);
    }
    public function modifierLocationAction(Request $request){
        $Location=new Location();
        $em = $this->getDoctrine()->getManager();
        $Location= $em->getRepository("ServiceBundle:Location")->find($request->get('id'));
        $Location->setNbJours($request->get('nbjours'));
        $Location->setPrixTot($request->get('prixfinal'));
        try {
            $Location->setDateArrivee(new \DateTime($request->get('datearrivee')));
        } catch (\Exception $e) {
        }
        $em=$this->getDoctrine()->getManager();
        $em->persist($Location);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Location);
        return new JsonResponse($formatted);
    }
    public function modifierReservationAction(Request $request){
        $Reservation=new Reservation();
        $em = $this->getDoctrine()->getManager();
        $Reservation= $em->getRepository("ServiceBundle:Reservation")->find($request->get('id'));
        $Reservation->setNbJours($request->get('nbjours'));
        $Reservation->setPrixTot($request->get('prixfinal'));
        try {
            $Reservation->setDateArrivee(new \DateTime($request->get('datearrivee')));
        } catch (\Exception $e) {
        }
        $em=$this->getDoctrine()->getManager();
        $em->persist($Reservation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Reservation);
        return new JsonResponse($formatted);
    }
    public function supprimerLocationAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $Location=$em->getRepository("ServiceBundle:Location")->find($id);
        $em->remove($Location);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Location);
        return new JsonResponse($formatted);
    }
    public function supprimerReservationAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $Reservation=$em->getRepository("ServiceBundle:Reservation")->find($id);
        $em->remove($Reservation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Reservation);
        return new JsonResponse($formatted);
    }
    public function searchhebergementfrontAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $hebergements= $em->getRepository("ServiceBundle:Hebergement")->findEntitiesByString($request->get('str'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($hebergements);
        return new JsonResponse($formatted);
    }
    public function searchtransportfrontAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $transports= $em->getRepository("ServiceBundle:MoyenDeTransport")->findEntitiesByString($request->get('str'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($transports);
        return new JsonResponse($formatted);
    }
}
