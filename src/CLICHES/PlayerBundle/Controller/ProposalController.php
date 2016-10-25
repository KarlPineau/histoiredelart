<?php

namespace CLICHES\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProposalController extends Controller
{
    public function proposalAction($playerOeuvre_id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $playerOeuvre = $repositoryPlayerOeuvre->findOneById($playerOeuvre_id);
        
        if ($playerOeuvre === null) {throw $this->createNotFoundException('Session : [id='.$playerOeuvre_id.'] inexistante.');}

        switch ($playerOeuvre->getPlayerSession()->getProposalType()) {
            case 'modeTest':
                return $this->redirectToRoute('cliches_player_proposaltest_proposaltest', array('playerOeuvre_id' => $playerOeuvre->getId()));
                break;
            case 'modeField':
                return $this->redirectToRoute('cliches_player_proposalfield_proposalfield', array('playerOeuvre_id' => $playerOeuvre->getId()));
                break;
            case 'modeChoice':
                return $this->redirectToRoute('cliches_player_proposalchoice_proposalchoice', array('playerOeuvre_id' => $playerOeuvre->getId()));
                break;
        }
    }
}
