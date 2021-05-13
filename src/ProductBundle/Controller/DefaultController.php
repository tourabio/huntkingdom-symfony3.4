<?php

namespace ProductBundle\Controller;

use ProductBundle\Entity\Promotion;
use ProductBundle\Form\PromotionType;
use ProductBundle\Entity\Produit;
use ProductBundle\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function productsAction()
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('@Product/dashboard/productss.html.twig', array(
            'produit' => $produit
        ));
    }

    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(Produit::class)->find($id);
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('list_product');
    }

    public function frontshopAction()
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('@Product/front/shop.html.twig', array(
            'produit' => $produit
        ));
    }

    public function frontshopsingleAction()
    {
        return $this->render('@Product/front/shop-single.html.twig');
    }

    public function productListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository("ProductBundle:Produit")->createQueryBuilder('p');
        if($request->query->getAlnum('filter')){
            $queryBuilder
                ->where('p.libProd LIKE :libProd')
                ->setParameter('libProd','%'.$request->query->getAlnum('filter').'%');
        }
        $query = $queryBuilder->getQuery();
        $paginator  = $this->get('knp_paginator');

        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',2)
        );


        return $this->render('@Product/dashboard/products.html.twig', [
            'produits' => $result,
        ]);
    }
    public function affectPromotionAction($id,Request $request)
    {
        $promotion = new Promotion();
        $date = new \DateTime();
        $form = $this->createForm(PromotionType::class, $promotion);
        $produit = $this->getDoctrine()->getRepository('ProductBundle:Produit')->find($id);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $promotion->setDateDebut($date);
            $produit->setPromotion($promotion);
            $produit->setPrixFinale($produit->getPrix() - $produit->getPrix() * $promotion->getTaux() ) ;
            $em->persist($promotion);
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('list_products');

        }


        return $this->render('@Product/dashboard/promotions.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    public function listPromotionAction()
    {
        $promotions = $this->getDoctrine()->getRepository('ProductBundle:Promotion')->findAll();
        return $this->render('@Product/dashboard/promotionslist.html.twig', [
            'promotions' => $promotions,
        ]);
    }
    public function deletePromotionAction($id)
    {
        $promotion = $this->getDoctrine()->getRepository('ProductBundle:Promotion')->find($id);
        $produit = $this->getDoctrine()->getRepository('ProductBundle:Produit')->findProduit($id);
        if ($produit) {
            $produit->setPromotion(null);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->remove($promotion);
        $em->flush();
        return $this->redirectToRoute('list_promotion');
    }

    public function modifierPromotionAction($id, Request $request)
    {
        $promotion = $this->getDoctrine()->getRepository('ProductBundle:Promotion')->find($id);
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $promotion = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            return $this->redirectToRoute('list_promotion');
        }

        return $this->render('@Product/dashboard/promotions.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
