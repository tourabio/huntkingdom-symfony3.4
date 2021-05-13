<?php



namespace ProductBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use ProductBundle\Entity\Panier;
use ProductBundle\Entity\Produit;
use ReparationBundle\Entity\Reparation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class apiController extends Controller
{
    public function AllAction(Request $request)//lister les pieces defectueuses non reserver (pour le reparateur)
    {
        $produits = $this->getDoctrine()->getManager()
            ->getRepository('ProductBundle:Produit')
            ->findAll();
        $session = $request->getSession();
        $panier = $session->get('panier');
        $session->set('panier', new ArrayCollection());

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produits);
        return new JsonResponse($formatted);
    }

    public function addProduitAction(Request $request)//ajouter une reparation (pour reparateur)
    {
        $produit = new Produit();
        $date = new \DateTime();
        $produit->setLibProd($request->get('lib_prod'));
        $produit->setPrix($request->get('prix'));
        $produit->setPrixFinale($produit->getPrix());
        $produit->setDescription($request->get('description'));
        $produit->setQteProd($request->get('qte_prod'));
        $produit->setDateAjout($date);
        $produit->setImage($request->get('image'));
        $produit->setType($request->get('type'));
        $produit->setMarque($request->get('marque'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse($formatted);
    }

    public function addtopanierAction(Request $request, $id)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
      /*  if (!isset($panier)) {
            $session->set('panier', new ArrayCollection());
            $produit = $this->getDoctrine()->getRepository('ProductBundle:Produit')->find($id);
            $panier = $session->get('panier');
            $panier->add($produit);
        } else {*/
            $produit = $this->getDoctrine()->getRepository('ProductBundle:Produit')->find($id);
            $panier = $session->get('panier');
            $panier->add($produit);
       // }

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($panier);
        return new JsonResponse($formatted);
    }



    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $this->getDoctrine()->getRepository('ProductBundle:Produit')->find($id);
        $session  = $request->getSession();
        $panier = $session->get('panier');
        foreach ($panier as $key =>  $value) {
            if ($value->getId() == $id) {
                break;
            }
        }
        unset($panier[$key]);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($panier);
        return new JsonResponse($formatted);
    }



    public function confirmAction(Request $request,$prixTotale,$to,$username,$address,$phoneNumber)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        $em = $this->getDoctrine()->getManager();
        $pan = new Panier();
        $pan->setNbArticles(count($panier));
        $pan->setPrixTotal($prixTotale);
        $pan->setDatePanier(new \DateTime());


        $em->persist($pan);
        $em->flush();
        $sujet='your bill';
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
                    'Emails/commande.html.twig',
                    [
                        'prixTotale'=>$prixTotale,
                        'table'=>$panier,
                        'current_date'=>new \DateTime(),
                        'username'=>$username,
                        'address'=>$address,
                        'email'=>$to,
                        'phoneNumber'=>$phoneNumber
                    ]
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);



        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize("mail sent");
        return new JsonResponse($formatted);

    }

}


