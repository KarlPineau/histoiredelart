<?php

namespace TB\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('TBAdministrationBundle:Home:index.html.twig');
    }
}
