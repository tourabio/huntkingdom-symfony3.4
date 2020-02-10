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
        else
        {
            return $this->redirectToRoute('fos_user_security_login');
        }

    }

}
