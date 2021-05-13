<?php

namespace ApiBundle\Controller;

use EventsBundle\Entity\Competition;
use EventsBundle\Entity\Publicity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EventMobileController extends Controller
{
    public function affichercompetitionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('EventsBundle:Competition')->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($competitions);
        return new JsonResponse($formatted);
    }
    public function afficherPubliciteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('EventsBundle:Publicity')->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($competitions);
        return new JsonResponse($formatted);
    }
    public function ajoutpublicityAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $publicity = new Publicity();
        $publicity->setCompagnie($request->get('compagnie'));
        $publicity->setTitre($request->get('titre'));
        $publicity->setPrix($request->get('prix'));
        $publicity->setDescription($request->get('description'));
        $publicity->setImage($request->get('image'));
        try {
            $publicity->setDateDebut(new \DateTime($request->get('dateDebut')));
        } catch (\Exception $e) {
        }
        try {
            $publicity->setDateFin(new \DateTime($request->get('dateFin')));
        } catch (\Exception $e) {
        }


        $em->persist($publicity);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($publicity);
        return new JsonResponse($formatted);
    }
    public function modifyPublicityAction(Request $request)
    {
        $publicity = new Publicity();
        $em=$this->getDoctrine()->getManager();
        $publicity=$em->getRepository("EventsBundle:Publicity")->find($request->get('id'));
        $publicity->setCompagnie($request->get('compagnie'));
        $publicity->setTitre($request->get('titre'));
        $publicity->setPrix($request->get('prix'));
        $publicity->setDescription($request->get('description'));
        $publicity->setImage($request->get('image'));
        try {
            $publicity->setDateDebut(new \DateTime($request->get('dateDebut')));
        } catch (\Exception $e) {
        }
        try {
            $publicity->setDateFin(new \DateTime($request->get('dateFin')));
        } catch (\Exception $e) {
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($publicity);
        return new JsonResponse($formatted);
    }
    public function supprimerpublicityAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $publicity=$em->getRepository("EventsBundle:Publicity")->find($id);
        $em->remove($publicity);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($publicity);
        return new JsonResponse($formatted);
    }
    public function ajoutompetitionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $competition = new Competition();
        $competition->setNom($request->get('nom'));
        $competition->setCategorie($request->get('categorie'));
        $competition->setLieu($request->get('lieu'));
        $competition->setNbParticipants($request->get('nbParticipants'));
        try {
            $competition->setDateDebut(new \DateTime($request->get('dateDebut')));
        } catch (\Exception $e) {
        }
        try {
            $competition->setDateFin(new \DateTime($request->get('dateFin')));
        } catch (\Exception $e) {
        }


        $em->persist($competition);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($competition);
        return new JsonResponse($formatted);
    }
    public function modifyCompetitionAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $competition = new Competition();
        $competition=$em->getRepository("EventsBundle:Competition")->find($request->get('id'));
        $competition->setNom($request->get('nom'));
        $competition->setCategorie($request->get('categorie'));
        $competition->setLieu($request->get('lieu'));
        $competition->setNbParticipants($request->get('nbParticipants'));
        try {
            $competition->setDateDebut(new \DateTime($request->get('dateDebut')));
        } catch (\Exception $e) {
        }
        try {
            $competition->setDateFin(new \DateTime($request->get('dateFin')));
        } catch (\Exception $e) {
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($competition);
        return new JsonResponse($formatted);
    }
    public function supprimercompetitionAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $competition=$em->getRepository("EventsBundle:Competition")->find($id);
        $em->remove($competition);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($competition);
        return new JsonResponse($formatted);
    }
    public function listParticipationCompetitionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('EventsBundle:Competition')->find($request->get('id'));

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($competition);
        return new JsonResponse($formatted);
    }
    public function addtocompetitionAction(Request $request)
    {
        $competition = $this->getDoctrine()->getRepository('EventsBundle:Competition')->find($request->get('idc'));
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->find($request->get('idu'));


        $competition->addtocompetition($user);
        $competition->setNbParticipants($competition->getNbParticipants()-1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($competition);
        $sujet='Competition '.$competition->getNom();
        $mailer = $this->container->get('mailer');
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com',465,'ssl')
            ->setUsername('mohamedsayed.tourabi@esprit.tn')
            ->setPassword('182JMT0297');

        $mailer=\Swift_Mailer::newInstance($transport);
        $message=\Swift_Message::newInstance('')
            ->setSubject($sujet)
            ->setFrom('mohamedsayed.tourabi@esprit.tn','HuntKingDom')
            ->setTo( $user->getEmail())
            ->setBody(" Your participation in the ".$sujet. " have been accepted " );
        $this->get('mailer')->send($message);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($competition);
        return new JsonResponse($formatted);
    }
    public function annulerParticipationAction(Request $request)
    {
        $competition = $this->getDoctrine()->getRepository('EventsBundle:Competition')->find($request->get('idc'));
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->find($request->get('idu'));


        $competition->removefromcompetition($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($competition);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($competition);
        return new JsonResponse($formatted);

    }
    public function searchCompetitionfrontAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $transports= $em->getRepository("EventsBundle:Competition")->findEntitiesByString($request->get('str'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($transports);
        return new JsonResponse($formatted);
    }


}
