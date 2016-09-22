<?php

namespace TOOLS\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('TOOLSHomeBundle:Home:index.html.twig');
    }
}
