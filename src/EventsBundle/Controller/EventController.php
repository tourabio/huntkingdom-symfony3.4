<?php

namespace EventsBundle\Controller;

use EventsBundle\Entity\Competition;
use EventsBundle\Entity\Publicity;
use EventsBundle\Form\CompetitionType;
use EventsBundle\Form\PublicityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    public function affichercompetitionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('EventsBundle:Competition')->findAll();

        return $this->render('@Events/dashboard/competition.html.twig', array('competitions' => $competitions));
    }

    public function competitionajoutAction(Request $request)
    {
        $competition = new Competition();
        $form_ajout = $this->createForm(CompetitionType::class, $competition);
        $form_ajout->handleRequest($request);
        if ($form_ajout->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($competition);
            $em->flush();
            return $this->redirect($this->generateUrl('list_competition'));
        }
        return $this->render('@Events/dashboard/competitionajout.html.twig', array('form' => $form_ajout->createView()));
    }

    public function afficherpubliciteAction()
    {
        $Publicity = new Publicity();
        $em = $this->getDoctrine()->getManager();
        $publicites = $em->getRepository('EventsBundle:Publicity')->findAll();

        return $this->render('@Events/dashboard/publicity.html.twig', array('publicites' => $publicites));
    }

    public function publicityajoutAction(Request $request)
    {
        $publicity = new Publicity();
        $form = $this->createForm(PublicityType::class, $publicity);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            if ($publicity->getImage() !== null){
                $file = $publicity->getImage();
                $filename = md5(uniqid()). '.' . $file->guessExtension();
                $file->move($this->getParameter('media_directory'), $filename);
                $publicity->setImage($filename);
            }
            else{
                $publicity->setImage('');
            }
            $em->persist($publicity);
            $em->flush();
            return $this->redirectToRoute('list_publicity');
        }
        return $this->render('@Events/dashboard/publicityajout.html.twig', array('form' => $form->createView()));
    }

    public function supprimerpublicityAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $publicity = $em->getRepository("EventsBundle:Publicity")->find($id);
        $em->remove($publicity);
        $em->flush();
        return $this->redirectToRoute("list_publicity");
    }

    public function modifierpublicityAction(Request $request, $id)
    {

        $publicity = new Publicity();
        $em = $this->getDoctrine()->getManager();

        $publicity = $em->getRepository(Publicity::class)->find($id);
        $form = $this->createForm(PublicityType::class, $publicity);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            if ($publicity->getImage() !== null){
                $file = $publicity->getImage();
                $filename = md5(uniqid()). '.' . $file->guessExtension();
                $file->move($this->getParameter('media_directory'), $filename);
                $publicity->setImage($filename);
            }
            else{
                $publicity->setImage('');
            }
            $em->flush();

            return $this->redirectToRoute('list_publicity');
        }

        return $this->render('@Events/dashboard/publicitymodifier.html.twig', array('form' => $form->createView()));

    }


    public function addtocompetitionAction(Request $request, $id)
    {
        $competition = $this->getDoctrine()->getRepository('EventsBundle:Competition')->find($id);
        $user = $this->getUser();


        $competition->addtocompetition($user);
        $competition->setNbParticipants($competition->getNbParticipants()-1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($competition);
        $em->flush();
        $mailer = $this->container->get('mailer');
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com',465,'ssl')
            ->setUsername('walid.haguiga@esprit.tn')
            ->setPassword('ronaldocristiano');
        $mailer=\Swift_Mailer::newInstance($transport);
        $message=\Swift_Message::newInstance('Test')
            ->setSubject('Participation confirmée')
            ->setFrom('walid.haguiga@esprit.tn','HuntKingdom')
            ->setTo($this->getUser()->getEmail())
            ->setBody('vous avez paricipé a la competition '.$competition->getNom());
        $this->get('mailer')->send($message);

        return $this->redirectToRoute("list_event");
    }
    /*

        $user = $this->getUser();
        $userid = $user->getId();
        $produit = $this->getDoctrine()->getRepository('ProductBundle:Produit')->find($id);
        $produits = $this->getDoctrine()->getRepository('ProductBundle:Produit')->findAll();
        $panier = $this->getDoctrine()->getRepository('ProductBundle:Panier')->findByShopid($userid);
        if ($panier == null) {
            $this->panier->addtopanier($produit);
            $this->panier->setPrixTotal($this->panier->getPrixTotal() + $produit->getPrix() * $produit->getQteProd());
            $this->panier->setNbArticles($this->panier->getNbArticles() + $produit->getQteProd());
            $em = $this->getDoctrine()->getManager();
            $this->panier->setShopid($userid);
            $em->persist($this->panier);
            $em->flush();
        } else {


            $this->panier->setPrixTotal($this->panier->getPrixTotal() + $produit->getPrix() * $produit->getQteProd());
            $this->panier->setNbArticles($this->panier->getNbArticles() + $produit->getQteProd());
            $em = $this->getDoctrine()->getManager();
            $this->panier->setShopid($userid);
            $em->persist($this->panier);
            $em->flush();
        }*/

    public function supprimercompetitionAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository("EventsBundle:Competition")->find($id);
        $em->remove($competition);
        $em->flush();

        $manager = $this->get('mgilet.notification');
        $notif = $manager->createNotification($competition->getNom());
        $notif->setMessage('');
        $notif->setLink('http://symfony.com/');
        // or the one-line method :
        // $manager->createNotification('Notification subject','Some random text','http://google.fr');

        // you can add a notification to a list of entities
        // the third parameter ``$flush`` allows you to directly flush the entities
        $manager->addNotification(array($this->getUser()), $notif, true);
        return $this->redirectToRoute("list_competition");
    }

    public function modifiercompetitionAction(Request $request, $id)
    {
        $competition = new Competition();
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository(Competition::class)->find($id);
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('list_competition');
        }
        return $this->render('@Events/dashboard/competitionmodifier.html.twig', array('form' => $form->createView()));
    }
    public function listcompetitionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('EventsBundle:Competition')->findAll();

        return $this->render('@Events/front/event.html.twig', array('competitions' => $competitions));
    }
    public function listPublicitesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $publicites = $em->getRepository('EventsBundle:Publicity')->findAll();

        return $this->render('@Events/front/event-single.html.twig', array('publicites' => $publicites));
    }
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('EventsBundle:Competition')->findAll();
        $writer = $this->container->get('egyg33k.csv.writer');
        $csv = $writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['nom', 'categorie', 'lieu', 'nbParticipants']);
        foreach ($competitions as $competition)
        {
            $csv->insertOne([$competition->getNom(),   $competition->getCategorie(),   $competition->getLieu(), $competition->getNbParticipants()]);
        }
        $csv->output('competitions.csv');
        exit;
    }

    public function recherchepublicityAction(Request $request)
    {
        $event=$request->get('Publicity');

        $events=$this->getDoctrine()->getManager()->createQuery("select e from EventsBundle:Publicity e where e.id like '%".$event."%'")
            ->getResult();

//die("aa");
        $jsonData=array();
        $idx=0;
        foreach ($events as $liv) {
            $temp=array(
                'id'=>$liv->getId(),
                'titre'=>$liv->getTitre(),
                'compagnie'=>$liv->getCompagnie(),
                'prix'=>$liv->getPrix(),
                'description'=>$liv->getDescription(),
                'dateDebut'=>$liv->getDateDebut(),
                'dateFin'=>$liv->getDateFin(),
                'image'=>$liv->getImage(),

            );
            $jsonData[$idx++]=$temp;

        }

        return new JsonResponse($jsonData);

//return
    }


}