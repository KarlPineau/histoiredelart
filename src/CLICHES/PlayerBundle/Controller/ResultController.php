<?php

namespace CLICHES\PlayerBundle\Controller;

use CLICHES\PlayerBundle\Form\PlayerSuggestEnrichedType;
use DATA\TeachingBundle\Entity\TeachingTest;
use DATA\TeachingBundle\Entity\TeachingTestVote;
use DATA\TeachingBundle\Form\TeachingTestVoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CLICHES\PlayerBundle\Entity\PlayerSuggest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ResultController extends Controller
{
    /* Méthode d'affichage des résultats une fois le formulaire rempli : */
    public function resultAction($playerProposal_id)
    {
        $em = $this->getDoctrine()->getManager();
        $playerProposal = $em->getRepository('CLICHESPlayerBundle:PlayerProposal')->findOneById($playerProposal_id);
        if ($playerProposal === null) { throw $this->createNotFoundException('Session : [id='.$playerProposal_id.'] inexistante.'); }

        switch ($playerProposal->getPlayerOeuvre()->getPlayerSession()->getProposalType()) {
            case 'modeField':
                return $this->redirectToRoute('cliches_player_resultfield_result', array('playerProposal_id' => $playerProposal->getId()));
                break;
            case 'modeChoice':
                return $this->redirectToRoute('cliches_player_resultchoice_result', array('playerProposal_id' => $playerProposal->getId()));
                break;
        }
    }
}
