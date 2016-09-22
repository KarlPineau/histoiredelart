<?php

namespace CLICHES\PersonalPlaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StatsController extends Controller
{
    public function statsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPlayerSession = $em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $repositoryPlayerProposal = $em->getRepository('CLICHESPlayerBundle:PlayerProposal');

        $playerSessions = $repositoryPlayerSession->findBy(array('createUser' => $this->getUser()), array('createDate' => 'DESC'));
        $endPlayerSession = array();
        foreach($playerSessions as $playerSession) {
            $playerOeuvres = $repositoryPlayerOeuvre->findByPlayerSession($playerSession);

            $playerOeuvresCollection = array();
            foreach($playerOeuvres as $key => $playerOeuvre) {
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

            $endPlayerSession[] =  ['playerSession' => $playerSession,
                                    'playerOeuvres' => $playerOeuvresCollection
                                    ];
        }

        $paginator  = $this->get('knp_paginator');
        $paginatorPlayerSessions = $paginator->paginate(
            $endPlayerSession,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('CLICHESPersonalPlaceBundle:Stats:stats.html.twig', array(
            'playerSessions' => $paginatorPlayerSessions,
        ));
    }
}
