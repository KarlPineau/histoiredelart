<?php

namespace CLICHES\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlayerSessionViewController extends Controller
{
    public function indexAction(Request $request)
    {
        $repositorySession = $this->getDoctrine()->getManager()->getRepository('CLICHESPlayerBundle:PlayerSession');
        $sessions = $repositorySession->findBy(array(), array('dateBegin' => 'DESC'));

        $paginator  = $this->get('knp_paginator');
        $listSessions = $paginator->paginate(
            $sessions,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );
        
        return $this->render('CLICHESAdministrationBundle:PlayerSessionView:index.html.twig', array(
                'sessions' => $listSessions
        ));
    }

    public function viewAction($session_id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositorySession = $em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $session = $repositorySession->findOneById($session_id);

        if ($session === null) { throw $this->createNotFoundException('Session : [id='.$session_id.'] inexistante.'); }

        $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $playerOeuvres = $repositoryPlayerOeuvre->findByPlayerSession($session);

        $playerOeuvresCollection = array();
        foreach($playerOeuvres as $key => $playerOeuvre) {
            $repositoryPlayerProposal = $em->getRepository('CLICHESPlayerBundle:PlayerProposal');
            $playerProposal = $repositoryPlayerProposal->findOneByPlayerOeuvre($playerOeuvre);

            $servicePlayerResult = $this->container->get('cliches_player.playerResultAction');

            if($playerProposal != null AND $servicePlayerResult->testFilled($playerProposal) == true) {
                $playerOeuvresCollection[$key] = ['playerOeuvre' => $playerOeuvre,
                    'playerProposal' => $servicePlayerResult->foundResults($playerProposal->getId(), false)];
            } else {
                $playerOeuvresCollection[$key] = ['playerOeuvre' => $playerOeuvre,
                    'playerProposal' => null];
            }
        }
        
        return $this->render('CLICHESAdministrationBundle:PlayerSessionView:view.html.twig', array(
            'session' => $session,
            'playerOeuvres' => $playerOeuvresCollection
        ));
    }
}
