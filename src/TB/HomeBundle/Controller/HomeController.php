<?php

namespace TB\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $testedGames = $em->getRepository('TBModelBundle:TestedGame')->findAll();

        return $this->render('TBHomeBundle:Home:index.html.twig', array('testedGames' => $testedGames));
    }
}
