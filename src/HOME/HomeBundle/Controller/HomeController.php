<?php

namespace HOME\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('HOMEHomeBundle:Home:index.html.twig');
    }
}
