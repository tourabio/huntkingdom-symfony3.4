<?php

namespace ServiceBundle\Controller;

use ServiceBundle\Entity\Hebergement;
use ServiceBundle\Entity\Location;
use ServiceBundle\Entity\MoyenDeTransport;
use ServiceBundle\Entity\Reservation;
use ServiceBundle\Form\HebergementType;
use ServiceBundle\Form\LocationType;
use ServiceBundle\Form\MoyenDeTransportType;
use ServiceBundle\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ServiceController extends Controller
{
    public function afficherhebergementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$hs= $em->getRepository("ServiceBundle:Hebergement")->createQ;
        $queryBuilder = $em->getRepository("ServiceBundle:Hebergement")->createQueryBuilder('bp');
        if($request->query->getAlnum('filter')){
            $queryBuilder
                ->where('bp.adresse LIKE :adresse')
                ->setParameter('adresse','%'.$request->query->getAlnum('filter').'%');
        }
        $query = $queryBuilder->getQuery();
        $paginator  = $this->get('knp_paginator');

        $hebergements = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',2)
        );
        return $this->render("@Service/dashboard/hebergement.html.twig", array('hebergements'=>$hebergements));
    }
    public function afficherunhebergementAction($id){
        $em = $this->getDoctrine()->getManager();
        $hebergements= $em->getRepository("ServiceBundle:Hebergement")->find($id);
        $reservations= $em->getRepository("ServiceBundle:Reservation")->findBy(array('Hebergement' => $hebergements));
        return $this->render('@Service/dashboard/unhebergement.html.twig',array('reservations'=>$reservations,'hebergements'=>$hebergements));
    }
    public function afficheruntransportAction($id){
        $em = $this->getDoctrine()->getManager();
        $transports= $em->getRepository("ServiceBundle:MoyenDeTransport")->find($id);
        $reservations= $em->getRepository("ServiceBundle:Location")->findBy(array('MoyenDeTransport' => $transports));
        return $this->render('@Service/dashboard/untransport.html.twig',array('reservations'=>$reservations,'transports'=>$transports));
    }
    public function supprimerhebergementAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $hebergement=$em->getRepository("ServiceBundle:Hebergement")->find($id);
        $hb=$hebergement->getId();
        $sujet='Hebergement '.$hebergement->getId().' supprimé';
        $em->remove($hebergement);
        $em->flush();
        $mailer = $this->container->get('mailer');
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com',465,'ssl')
            ->setUsername('khaledhaguiga@gmail.com')
            ->setPassword('khaledhaguiga@yahoo.fr');

        $mailer=\Swift_Mailer::newInstance($transport);
        $message=\Swift_Message::newInstance('')
            ->setSubject($sujet)
            ->setFrom('khaledhaguiga@gmail.com')
            ->setTo( $this->getUser()->getEmail())
            ->setBody("l'admin ".$this->getUser()->getEmail()." a supprimé l'hébergement ".$hb);
        $this->get('mailer')->send($message);
        return $this->redirectToRoute('list_accommodation');
    }
    public function ajouterhebergementAction(Request $request){
        $hebergement=new Hebergement();
        $form=$this->createForm(HebergementType::class,$hebergement);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            if ($hebergement->getImage() !== null){
                $file = $hebergement->getImage();
                $filename = md5(uniqid()). '.' . $file->guessExtension();
                $file->move($this->getParameter('media_directory'), $filename);
                $hebergement->setImage($filename);
            }
            else{
                $hebergement->setImage(' ');
            }
            $em->persist($hebergement);
            $em->flush();
            return $this->redirectToRoute('list_accommodation');
        }
        return $this->render('@Service/dashboard/addhebergement.html.twig',array('form'=>$form->createView()));
    }
    public function modifierhebergementAction(Request $request, $id)
    {

        $hebergement = new Hebergement();
        $em = $this->getDoctrine()->getManager();
        $hebergement = $em->getRepository(Hebergement::class)->find($id);
        $form = $this->createForm(HebergementType::class, $hebergement);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            if ($hebergement->getImage() !== null){
                $file = $hebergement->getImage();
                $filename = md5(uniqid()). '.' . $file->guessExtension();
                $file->move($this->getParameter('media_directory'), $filename);
                $hebergement->setImage($filename);
            }
            else{
                $hebergement->setImage(' ');
            }
            $em->flush();

            return $this->redirectToRoute('list_accommodation');
        }

        return $this->render('@Service/dashboard/modifyhebergement.html.twig', array('form' => $form->createView()));

    }
    public function affichertransportAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$transports= $em->getRepository("ServiceBundle:MoyenDeTransport")->findAll();
        $queryBuilder = $em->getRepository("ServiceBundle:MoyenDeTransport")->createQueryBuilder('bp');
        if($request->query->getAlnum('filter')){
            $queryBuilder
                ->where('bp.marque LIKE :marque')
                ->setParameter('marque','%'.$request->query->getAlnum('filter').'%');
        }
        $query = $queryBuilder->getQuery();
        $paginator  = $this->get('knp_paginator');

        $transports = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',2)
        );
        return $this->render("@Service/dashboard/transport.html.twig", array('transports'=>$transports));
    }
    public function ajoutertransportAction(Request $request){
        $transport=new MoyenDeTransport();
        $form=$this->createForm(MoyenDeTransportType::class,$transport);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            if ($transport->getImage() !== null){
                $file = $transport->getImage();
                $filename = md5(uniqid()). '.' . $file->guessExtension();
                $file->move($this->getParameter('media_directory'), $filename);
                $transport->setImage($filename);
            }
            else{
                $transport->setImage(' ');
            }
            $em->persist($transport);
            $em->flush();
            return $this->redirectToRoute('list_transport');
        }
        return $this->render('@Service/dashboard/addtransport.html.twig',array('form'=>$form->createView()));
    }
    public function modifiertransportAction(Request $request, $id)
    {

        $transport = new MoyenDeTransport();
        $em = $this->getDoctrine()->getManager();
        $transport = $em->getRepository(MoyenDeTransport::class)->find($id);
        $form = $this->createForm(MoyenDeTransportType::class, $transport);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('list_transport');
        }

        return $this->render('@Service/dashboard/modifytransport.html.twig', array('form' => $form->createView()));
    }
    public function supprimertransportAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $transport=$em->getRepository("ServiceBundle:MoyenDeTransport")->find($id);
        $em->remove($transport);
        $em->flush();
        return $this->redirectToRoute('list_transport');
    }
    public function afficherhebergementfrontAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$hebergements= $em->getRepository("ServiceBundle:Hebergement")->findAll();
        $queryBuilder = $em->getRepository("ServiceBundle:Hebergement")->createQueryBuilder('bp');

        $query = $queryBuilder->getQuery();
        $paginator  = $this->get('knp_paginator');

        $hebergements = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',2)
        );
        return $this->render("@Service/front/hebergement.html.twig", array('hebergements'=>$hebergements));
    }
    #public function afficherunhebergementfrontAction($id)
    #{
     #   $em = $this->getDoctrine()->getManager();
      #  $hebergements= $em->getRepository("ServiceBundle:Hebergement")->find($id);
       # return $this->render("@Service/front/unhebergement.html.twig", array('hebergements'=>$hebergements));
    #}
    public function affichertransportfrontAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$transports= $em->getRepository("ServiceBundle:MoyenDeTransport")->findAll();
        $queryBuilder = $em->getRepository("ServiceBundle:MoyenDeTransport")->createQueryBuilder('bp');

        $query = $queryBuilder->getQuery();
        $paginator  = $this->get('knp_paginator');

        $transports = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',2)
        );
        return $this->render("@Service/front/transport.html.twig", array('transports'=>$transports));
    }
    #public function afficheruntransportfrontAction($id)
    #{
     #   $em = $this->getDoctrine()->getManager();
      #  $transports= $em->getRepository("ServiceBundle:MoyenDeTransport")->find($id);
       # return $this->render("@Service/front/untransport.html.twig", array('transports'=>$transports));
    #}
    public function afficherunhebergementfrontAction(Request $request,$id){
        $reservation=new Reservation();
        $em = $this->getDoctrine()->getManager();
        $hebergements= $em->getRepository("ServiceBundle:Hebergement")->find($id);
        $reservations= $em->getRepository("ServiceBundle:Reservation")->findBy(array('Hebergement' => $hebergements));
        $user = $this->getUser();
        $form=$this->createForm(ReservationType::class,$reservation);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            //$reservation->setPrixTot($hebergements->getPrixParJour()*$reservation->getNbJours());
            $reservation->setUser($user);
            $reservation->setHebergement($hebergements);
            $em=$this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute('accommodation_front');
        }
        return $this->render('@Service/front/unhebergement.html.twig',array('form'=>$form->createView(),'reservations'=>$reservations,'hebergements'=>$hebergements));
    }
    public function afficheruntransportfrontAction(Request $request,$id){
        $location=new Location();
        $em = $this->getDoctrine()->getManager();
        $transports= $em->getRepository("ServiceBundle:MoyenDeTransport")->find($id);
        $reservations= $em->getRepository("ServiceBundle:Location")->findBy(array('MoyenDeTransport' => $transports));
        $user = $this->getUser();
        $form=$this->createForm(LocationType::class,$location);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            //$location->setPrixTot($transports->getPrixParJour()*$location->getNbJours());
            $location->setUser($user);
            $location->setMoyenDeTransport($transports);
            $em=$this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();
            return $this->redirectToRoute('transport_front');
        }
        return $this->render('@Service/front/untransport.html.twig',array('form'=>$form->createView(),'reservations'=>$reservations,'transports'=>$transports));
    }
    public function affichermyfrontAction(){
        $em = $this->getDoctrine()->getManager();
        $locations= $em->getRepository("ServiceBundle:Location")->findBy(array('user' => $this->getUser()));
        $reservations= $em->getRepository("ServiceBundle:Reservation")->findBy(array('user' => $this->getUser()));
        return $this->render('@Service/front/my.html.twig',array('reservations'=>$reservations,'locations'=>$locations));
    }
}
