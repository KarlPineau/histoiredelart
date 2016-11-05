<?php

namespace TB\TestedGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TestedSessionController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $testedSessionsArray = $em->getRepository('TBModelBundle:TestedSession')->findBy(array(), array('createDate' => 'DESC'));

        $paginator  = $this->get('knp_paginator');
        $testedSessions = $paginator->paginate(
            $testedSessionsArray,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );

        return $this->render('TBTestedGameBundle:TestedSession:index.html.twig', array(
            'testedSessions' => $testedSessions,
        ));
    }

    public function getByTestedGameAction($testedGame_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if($testedGame === null) {throw $this->createNotFoundException('TestedGame non trouvé :'.$testedGame_id);}
        $testedSessionsArray = $em->getRepository('TBModelBundle:TestedSession')->findBy(array('testedGame' => $testedGame), array('createDate' => 'DESC'));

        $paginator  = $this->get('knp_paginator');
        $testedSessions = $paginator->paginate(
            $testedSessionsArray,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );

        return $this->render('TBTestedGameBundle:TestedSession:GetByTestedGame/index.html.twig', array(
            'testedGame' => $testedGame,
            'testedSessions' => $testedSessions,
        ));
    }

    public function viewAction($testedSession_id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedSession = $em->getRepository('TBModelBundle:TestedSession')->findOneById($testedSession_id);
        if($testedSession === null) {throw $this->createNotFoundException('TestedSession non trouvé :'.$testedSession_id);}

        return $this->render('TBTestedGameBundle:TestedSession:View/view.html.twig', array(
            'testedSession' => $testedSession,
        ));
    }
}
