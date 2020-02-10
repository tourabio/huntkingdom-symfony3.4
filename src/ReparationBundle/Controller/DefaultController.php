<?php

namespace ReparationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function reparationAction()
    {
        return $this->render('@Reparation/dashboard/reparation.html.twig');
    }
    public function promotionAction()
    {
        return $this->render('@Reparation/dashboard/promotion.html.twig');
    }
    public function frontreparationAction()
    {
        return $this->render('@Reparation/front/reparation.html.twig');
    }
    public function frontpromotionAction()
    {
        return $this->render('@Reparation/front/promotion.html.twig');
    }
}
