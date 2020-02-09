<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('main_dashboard');
        }
        else if
        ($this->get('security.authorization_checker')->isGranted('ROLE_REPAIRER')) {
            return $this->redirectToRoute('main_front');
        }

        else if
        ($this->get('security.authorization_checker')->isGranted('ROLE_TRAINER')) {
            return $this->redirectToRoute('main_front');
        }
        else if
        ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            return $this->redirectToRoute('main_front');
        }

        

    }
    public function adminAction()
    {
        return $this->render('@User/Default/admin.html.twig');
    }

    public function clientAction()
    {
        return $this->render('@User/Default/index.html.twig');
    }
}
