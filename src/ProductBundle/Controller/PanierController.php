<?php

namespace ProductBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use ProductBundle\Entity\Panier;
use ProductBundle\Form\PanierType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;


class PanierController extends Controller
{

    public function readAction(Request $request)

    {
        $session  = $request->getSession();
        $panier = $session->get('panier');
        /*
        $user = $this->getUser();
        $userid = $user->getId();

        $panier = $this->getDoctrine()->getRepository(Panier::class)->findByShopid($userid);*/
        return $this->render('@Product/Panier/read.html.twig', array(
            'panier' => $panier

        ));
    }

    public function createAction(Request $request)
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form = $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panier);
            $em->flush();
            return $this->redirectToRoute('readp');
        }
        return $this->render('@Product/Panier/create.html.twig', array(
            'form' => $form->createView()
        ));
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
        return $this->redirectToRoute('readp');
    }

    public function updateAction(Request $request, $id)
    {
        $panier = $this->getDoctrine()->getRepository('ProductBundle:Panier')->find($id);
        $form = $this->createForm(PanierType::class, $panier);
        $form->remove('Ajouter');
        $form->add('Modify', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $club = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($panier);
            $em->flush();
            return $this->redirectToRoute('readp');
        }
        return $this->render('@Product/Panier/update.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function addtopanierAction(Request $request, $id)
    {

         $session  = $request->getSession();
         $panier = $session->get('panier');
         if (! isset($panier)){
             $session->set('panier',new ArrayCollection());
             $produit = $this->getDoctrine()->getRepository('ProductBundle:Produit')->find($id);
             $panier = $session->get('panier');
             $panier->add($produit);
         }
         else
         {
             $produit = $this->getDoctrine()->getRepository('ProductBundle:Produit')->find($id);
             $panier = $session->get('panier');
             $panier->add($produit);
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

        return $this->redirectToRoute('shop');

        /*return $this->render('@Product/Product/read.html.twig', array(
            'produit'=>$produits
        ));*/
    }


    public function checkoutAction(Request $request,$prixTotale)
    {
        $session  = $request->getSession();
        $panier = $session->get('panier');
        $session->set('prixTotale',$prixTotale);
       /* $em = $this->getDoctrine()->getManager();
        $pan = new Panier();
        $pan->setNbArticles(count($panier));
        $pan->setPrixTotal($prixTotale);
        $em->persist($pan);
        $em->flush();*/

        return new JsonResponse($prixTotale);
        /*return $this->render('@Product/Commandes/commande.html.twig', array(
            'panier'=>$panier
        ));*/


        /*
        $user = new User();
        $user = $this->getUser();
        $userid = $user->getId();
        $panier = $this->getDoctrine()->getRepository('ProductBundle:Panier')->findByShopid($userid);
        $em = $this->getDoctrine()->getManager();
        $em->remove($panier);
        $em->flush();*/
    }


 public function confirmAction(Request $request)
{
    $session = $request->getSession();
    $panier = $session->get('panier');
    $prixTotale = $session->get('prixTotale');
    $em = $this->getDoctrine()->getManager();
    $pan = new Panier();
    $pan->setNbArticles(count($panier));
    $pan->setPrixTotal($prixTotale+0);
    $pan->setDatePanier(new \DateTime());
    $em->persist($pan);
    $em->flush();
    $snappy = $this->get('knp_snappy.pdf');
    $html = $this->renderView('@Product/Commandes/commande.html.twig', array(
    'prixTotale'=>$prixTotale,
    'table'=>$panier,
     'current_date'=>new \DateTime()
    ));
    $filename = "bill" ;
    return new Response(
        $snappy->getOutputFromHtml($html),
        200,
        array(
            'Content-Type'          => 'application/pdf',
            'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
        )
    );

}



/*continue to shipping ( ba3d lcheckout)


n7ott 5edmet lpdf houni
wou ba3d'ha redirection To Route
remarque : n7ott fl pdf(idcommande)


*/
}

