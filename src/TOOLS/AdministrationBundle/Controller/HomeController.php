<?php

namespace TOOLS\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('TOOLSAdministrationBundle:Home:index.html.twig');
    }
}
