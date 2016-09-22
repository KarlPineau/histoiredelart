<?php

namespace CLICHES\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CLICHES\PlayerBundle\Entity\PlayerOeuvre;

class SelectionController extends Controller
{
    /* Méthode sélectionnant l'oeuvre à afficher : */
    public function selectionAction($playerSession_id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositorySession = $em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $selectionService = $this->container->get('cliches_player.playerselectionaction');

        $playerSession = $repositorySession->findOneById($playerSession_id);
          
        if ($playerSession === null) {throw $this->createNotFoundException('Session : [id='.$playerSession_id.'] inexistante.');}

        if(!empty($playerSession->getDateEnd()) AND date_diff(new \DateTime(), $playerSession->getDateEnd())->format('%h') > 0) {
            $this->get('session')->getFlashBag()->add('notice', 'Oups... Cette session a expiré ...' );
            return $this->redirect($this->generateUrl('cliches_player_end_end', array('playerSession_id' => $playerSession->getId())));
        }
        
        $passedPlayerOeuvres = $repositoryPlayerOeuvre->findByPlayerSession($playerSession);
        $array_result = $selectionService->getRandViewForTeaching($playerSession->getTeaching(), $passedPlayerOeuvres);

        if($array_result[0] == 'no_enough_entities' OR $array_result[0] == 'no_entities') {
            $this->get('session')->getFlashBag()->add('notice', 'Oups... Il n\'a pas assez d\'oeuvres pour continuer.' );
            return $this->redirect($this->generateUrl('cliches_player_end_end', array('playerSession_id' => $playerSession->getId())));
        } elseif($array_result[0] == 'view') {
            $playerOeuvre = new PlayerOeuvre;
            $playerOeuvre->setPlayerSession($playerSession);
            $playerOeuvre->setView($array_result[1]);
            $em->persist($playerOeuvre);

            $playerSession->setDateEnd(new \DateTime());
            $em->persist($playerSession);

            $em->flush();

            return $this->redirect($this->generateUrl('cliches_player_proposal_proposal', array('playerOeuvre_id' => $playerOeuvre->getId())));
        }
    }
}
