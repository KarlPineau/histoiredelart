<?php

namespace TB\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $testedGames = $em->getRepository('TBModelBundle:TestedGame')->findBy(array('isOnline' => true), array('createDate' => 'DESC'), 10);

        return $this->render('TBHomeBundle:Home:index.html.twig', array('testedGames' => $testedGames));
    }

    public function registrationArgAction()
    {
        return $this->render('TBHomeBundle:Home:registrationArg.html.twig');
    }

    public function getAllTestedGamesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $testedGames = $em->getRepository('TBModelBundle:TestedGame')->findBy(array('isOnline' => true), array('createDate' => 'DESC'));

        return $this->render('TBHomeBundle:Home:getAllTestedGame.html.twig', array('testedGames' => $testedGames));
    }
}
