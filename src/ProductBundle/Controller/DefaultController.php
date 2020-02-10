<?php

namespace ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function productsAction()
    {
        return $this->render('@Product/dashboard/products.html.twig');
    }
    public function frontshopAction()
    {
        return $this->render('@Product/front/shop.html.twig');
    }
    public function frontshopsingleAction()
    {
        return $this->render('@Product/front/shop-single.html.twig');
    }
}
