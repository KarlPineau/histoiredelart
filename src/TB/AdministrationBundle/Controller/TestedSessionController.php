<?php

namespace TB\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestedSessionController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $testedSessions = $em->getRepository('TBModelBundle:TestedSession')->findBy(array(), array('createDate' => 'DESC'));

        return $this->render('TBAdministrationBundle:TestedSession:index.html.twig', array(
            'testedSessions' => $testedSessions,
        ));
    }

    public function getByTestedGameAction($testedGame_id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if($testedGame === null) {throw $this->createNotFoundException('TestedGame non trouvé :'.$testedGame_id);}
        $testedSessions = $em->getRepository('TBModelBundle:TestedSession')->findByTestedGame($testedGame);

        return $this->render('TBAdministrationBundle:TestedSession:GetByTestedGame/index.html.twig', array(
            'testedGame' => $testedGame,
            'testedSessions' => $testedSessions,
        ));
    }

    public function viewAction($testedSession_id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedSession = $em->getRepository('TBModelBundle:TestedSession')->findOneById($testedSession_id);
        if($testedSession === null) {throw $this->createNotFoundException('TestedSession non trouvé :'.$testedSession_id);}

        return $this->render('TBAdministrationBundle:TestedSession:View/view.html.twig', array(
            'testedSession' => $testedSession,
        ));
    }
}
