<?php

namespace TB\PersonalPlaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $testedGames = $em->getRepository('TBModelBundle:TestedGame')->findBy(array('createUser' => $this->getUser()));

        return $this->render('TBPersonalPlaceBundle:Home:index.html.twig', array('testedGames' => $testedGames));
    }
}
