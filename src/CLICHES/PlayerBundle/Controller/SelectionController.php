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
        $playerSession = $em->getRepository('CLICHESPlayerBundle:PlayerSession')->findOneById($playerSession_id);
        if ($playerSession === null) {throw $this->createNotFoundException('Session : [id='.$playerSession_id.'] inexistante.');}

        if(!empty($playerSession->getDateEnd()) AND date_diff(new \DateTime(), $playerSession->getDateEnd())->format('%h') > 0) {
            $this->get('session')->getFlashBag()->add('notice', 'Oups... Cette session a expiré ...' );
            return $this->redirect($this->generateUrl('cliches_player_end_end', array('playerSession_id' => $playerSession->getId())));
        }
        
        $passedPlayerOeuvres = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre')->findByPlayerSession($playerSession);
        $array_result = $this->get('cliches_player.playerselectionaction')->getRandViewForTeaching($playerSession->getTeaching(), $passedPlayerOeuvres);

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
