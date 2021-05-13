<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    public function indexAction()
    {
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

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $condidates = $this->getDoctrine()->getRepository('UserBundle:User')->findBy(array('confirmed' => false));
            if($condidates){
            $manager = $this->get('mgilet.notification');
            $notif = $manager->createNotification('hello');
            $notif->setMessage('condidates are waiting for your answer !');
            $notif->setLink('http://symfony.com/');
            // or the one-line method :
            // $manager->createNotification('Notification subject','Some random text','http://google.fr');

            // you can add a notification to a list of entities
            // the third parameter ``$flush`` allows you to directly flush the entities
            $manager->addNotification(array($this->getUser()), $notif, true);
            }

            return $this->redirectToRoute('main_dashboard');
        }
        else if
        ($this->get('security.authorization_checker')->isGranted('ROLE_REPAIRER')) {
            if($this->getUser()->isConfirmed()==false){
                return $this->redirectToRoute('please_wait');
            }
            else
            return $this->redirectToRoute('main_front');
        }

        else if
        ($this->get('security.authorization_checker')->isGranted('ROLE_TRAINER')) {
            if($this->getUser()->isConfirmed()==false){
                return $this->redirectToRoute('please_wait');
            }
            else
                return $this->redirectToRoute('main_front');
        }
        else if
        ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
                return $this->redirectToRoute('main_front');
        }
        else
        {
            return $this->redirectToRoute('fos_user_security_login');
        }


    }
    public function list_condidatesAction()
    {
        $condidates = $this->getDoctrine()->getRepository('UserBundle:User')->findBy(array('confirmed' => false));
        return $this->render('@User/condidates/list_condidates.html.twig', [
            'condidates' => $condidates,
        ]);
    }

    public function accept_condidateAction($id)
    {
        $condidate = $this->getDoctrine()->getRepository('UserBundle:User')->find($id);
        $condidate->setConfirmed(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($condidate);
        $em->flush();

        $sujet='Admin answer';
        $mailer = $this->container->get('mailer');
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com',465,'ssl')
            ->setUsername('mohamedsayed.tourabi@esprit.tn')
            ->setPassword('182JMT0297');

        $mailer=\Swift_Mailer::newInstance($transport);
        $message=\Swift_Message::newInstance('')
            ->setSubject($sujet)
            ->setFrom('mohamedsayed.tourabi@esprit.tn')
            ->setTo( $condidate->getEmail())
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'Emails/registration.html.twig'
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
        return $this->redirectToRoute('list_condidates');

    }
    public function reject_condidateAction($id)
    {
        $condidate = $this->getDoctrine()->getRepository('UserBundle:User')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($condidate);
        $em->flush();
        $sujet='Admin answer';
        $mailer = $this->container->get('mailer');
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com',465,'ssl')
            ->setUsername('mohamedsayed.tourabi@esprit.tn')
            ->setPassword('182JMT0297');

        $mailer=\Swift_Mailer::newInstance($transport);
        $message=\Swift_Message::newInstance('')
            ->setSubject($sujet)
            ->setFrom('mohamedsayed.tourabi@esprit.tn')
            ->setTo( $condidate->getEmail())
            ->setBody( $this->renderView(
            // app/Resources/views/Emails/registration.html.twig
                'Emails/rejection.html.twig'
            ),
                'text/html'
            );
        $this->get('mailer')->send($message);


        return $this->redirectToRoute('list_condidates');

    }


}
