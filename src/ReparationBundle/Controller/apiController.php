<?php

namespace ReparationBundle\Controller;

use ReparationBundle\Entity\Piecesdefectueuses;
use ReparationBundle\Entity\Reparation;
use ReparationBundle\Form\ReparationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\DBAL\Types\Type;
Type::overrideType('datetime', 'Doctrine\DBAL\Types\VarDateTimeType');
Type::overrideType('datetimetz', 'Doctrine\DBAL\Types\VarDateTimeType');
Type::overrideType('time', 'Doctrine\DBAL\Types\VarDateTimeType');
class apiController extends Controller
{
    public function AllAction()//lister les pieces defectueuses non reserver (pour le reparateur)
    {
        $pieces = $this->getDoctrine()->getManager()
            ->getRepository('ReparationBundle:Piecesdefectueuses')
            ->findDefective();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($pieces);
        return new JsonResponse($formatted);
    }

    public function add_defectiveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $piece = new Piecesdefectueuses();
        $piece->setNom($request->get('nom'));
        $piece->setCategorie($request->get('categorie'));
        $piece->setDescription($request->get('description'));
        $piece->setImage($request->get('image'));
        $s = $request->get('user');
        $id = (int)$s;
        $user = $this->getDoctrine()->getManager()
            ->getRepository('UserBundle:User')
            ->find($id);
        $piece->setUser($user); //expected user got string instead !
        $piece->setReserved(false);
        $em->persist($piece);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($piece);
        return new JsonResponse($formatted);
    }

    public function list_you_defectiveAction($idUser)
    {

        $pieces = $this->getDoctrine()->getRepository('ReparationBundle:Piecesdefectueuses')->find_my_Defective($idUser);
        $date = new \DateTime();
        $reparations_ready = $this->getDoctrine()->getRepository('ReparationBundle:Reparation')->find_my_ready($idUser, $date);
        $em = $this->getDoctrine()->getManager();
        foreach ($reparations_ready as $reparation_ready) {
            $piece_ready = $reparation_ready->getPiecedefectueuse();
            $piece_ready->setEtat(true);
            $em->persist($piece_ready);
        }

        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($pieces);
        return new JsonResponse($formatted);
    }

    public function show_progressAction($idP)
    {
        $reparation = $this->getDoctrine()->getRepository('ReparationBundle:Reparation')->find_my_reparation($idP);
        $piece = $this->getDoctrine()->getRepository('ReparationBundle:Piecesdefectueuses')->find($idP);

        $current_date = new \DateTime();
        $date_fin = $reparation->getDateFin();
        $timediff = $current_date->diff($date_fin);
        $years = $timediff->y;
        $months = $timediff->m;
        $days = $timediff->d;
        $seconds = $timediff->s;
        $minutes = $timediff->i;
        $hours = $timediff->h;
        $array_merged = [
            "reparation" => $reparation,
            "piece" => $piece,
            "years" => $years,
            "months" => $months,
            "days" => $days,
            "hours" => $hours,
            "minutes" => $minutes];
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($array_merged);
        return new JsonResponse($formatted);
    }

    public function repareAction($idP, $idR, Request $request)//ajouter une reparation (pour reparateur)
    {

        $reparation = new Reparation();
        $piece = $this->getDoctrine()->getRepository('ReparationBundle:Piecesdefectueuses')->find($idP);
        $repairer = $this->getDoctrine()->getRepository('UserBundle:User')->find($idR);
        $date = new \DateTime();
        $reparation->setPrixRep($request->get('prixRep'));
        $reparation->setDescription($request->get('description'));
        $s1 = $request->get('dateDebut');
        $s = $request->get('dateFin');
        $date = date_create_from_format('Y-m-d H:i:s', $s);
        $date1 = date_create_from_format('Y-m-d H:i:s', $s1);
         $date->getTimestamp();
        $reparation->setDateFin($date);
        $reparation->setDateDebut($date1);

        $em = $this->getDoctrine()->getManager();
            $piece->setReserved(true);
            $reparation->setPiecedefectueuse($piece);
            $reparation->setReparateur($repairer);
            $em->persist($reparation);
            $em->flush();


            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($reparation);
            return new JsonResponse($formatted);

    }

    public function show_readyAction($idP)//retourner la piece + sa reparation(pour client (piece deja reparée))
    {
        $reparation = $this->getDoctrine()->getRepository('ReparationBundle:Reparation')->find_my_reparation($idP);
        $date_fin =$reparation->getDateFin();
        $date_debut =$reparation->getDateDebut();
        $result = $date_fin->format('Y-m-d H:i:s');
        $result2 = $date_debut->format('Y-m-d H:i:s');

        $array_merged = [
            "reparation" => $reparation,
            "dateFinale"=>$result,
            "dateDebut"=>$result2
            ];
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($array_merged);
        return new JsonResponse($formatted);

    }
    public function finish_reparationAction($idR,$idP)
    {
        $piece = $this->getDoctrine()->getRepository('ReparationBundle:Piecesdefectueuses')->find($idP);
        $reparation = $this->getDoctrine()->getRepository('ReparationBundle:Reparation')->find($idR);
        if ($reparation) {
            $reparation->setPiecedefectueuse(null);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($piece);
        $em->remove($reparation);
        $em->flush();
        /*$id = $this->getUser()->getId();
        return $this->redirectToRoute('reparation_front_list_your_defective',[
            'idUser' => $id
        ]);*/
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize("reparation finished");
        return new JsonResponse($formatted);
    }

    public function show_repairer_profileAction($idR)
    {
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->find($idR);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);
    }
    public function count_your_readyAction($idUser)
    {
        $nb =(int) $this->getDoctrine()->getRepository('ReparationBundle:Piecesdefectueuses')->count_myReady($idUser);

        $array_merged = [
            "count" => $nb,
            ];
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($array_merged);
        return new JsonResponse($formatted);

    }
    public function searchAction($name)
    {
        $pieces = $this->getDoctrine()->getRepository('ReparationBundle:Piecesdefectueuses')->search_by_name($name);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($pieces);
        return new JsonResponse($formatted);

    }
    public function send_EmailAction($to)
    {

        $sujet='Admin answer';
        $mailer = $this->container->get('mailer');
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com',465,'ssl')
            ->setUsername('mohamedsayed.tourabi@esprit.tn')
            ->setPassword('182JMT0297');

        $mailer=\Swift_Mailer::newInstance($transport);
        $message=\Swift_Message::newInstance('')
            ->setSubject($sujet)
            ->setFrom('mohamedsayed.tourabi@esprit.tn')
            ->setTo($to)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'Emails/finish.html.twig'
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize("mail sent");
        return new JsonResponse($formatted);

    }
    public function listPromotionAction()
    {
        $promo = $this->getDoctrine()->getRepository('ProductBundle:Produit')->list_promotion();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($promo);
        return new JsonResponse($formatted);

    }
    public function endPromotionAction(){
        $date = new \DateTime();
        //$promotions = $this->getDoctrine()->getRepository('ProductBundle:Promotion')->findFinished($date);
        $produits = $this->getDoctrine()->getRepository('ProductBundle:Produit')->findProduitWithFineshed($date);
        //var_dump( $produits[0]->getPromotion()->getId() ).die();
        $em = $this->getDoctrine()->getManager();
        foreach ($produits as $produitFinished){
            $promotionsFinished = $produitFinished->getPromotion();
            $produitFinished->setPromotion(null);
            $produitFinished->setPrixFinale( $produitFinished->getPrix() ) ;
            $em->persist($produitFinished);
            $em->remove($promotionsFinished);
        }
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize("finished promotions deleted");
        return new JsonResponse($formatted);
    }
    public function isExistAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $piece = new Piecesdefectueuses();
        $piece->setNom($request->get('nom'));
        $piece->setCategorie($request->get('categorie'));
        $piece->setDescription($request->get('description'));
        $piece->setImage($request->get('image'));
        $s = $request->get('user');
        $id = (int)$s;
        $user = $this->getDoctrine()->getManager()
            ->getRepository('UserBundle:User')
            ->find($id);
        $piece->setUser($user); //expected user got string instead !
        $piece->setReserved(false);
        $piece->setEtat(false);
        $reponse="false";
        
        
        $pieces = $this->getDoctrine()->getRepository('ReparationBundle:Piecesdefectueuses')->findAll();
        foreach ($pieces as $p) {
        if($p->isEtat()==$piece->isEtat() && $p->isReserved()==$piece->isReserved() && $p->getUser()==$piece->getUser() && $p->getCategorie()==$piece->getCategorie() && $p->getNom()==$piece->getNom()&& $p->getDescription()==$piece->getDescription())//manque limage!
            $reponse="exists";
        }
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reponse);
        return new JsonResponse($formatted);
    }



}
