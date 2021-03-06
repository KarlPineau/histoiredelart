<?php

namespace TB\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $testedGames = $em->getRepository('TBModelBundle:TestedGame')->findBy(array(), array('createDate' => 'DESC'), 20);
        $testedSessions = $em->getRepository('TBModelBundle:TestedSession')->findBy(array(), array('createDate' => 'DESC'), 50);

        return $this->render('TBAdministrationBundle:Home:index.html.twig', array(
            'testedGames' => $testedGames,
            'testedSessions' => $testedSessions
        ));
    }
}
