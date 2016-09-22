<?php

namespace CLICHES\PlayerBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerSuggest;
use CLICHES\PlayerBundle\Form\PlayerSuggestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlayerSuggestController extends Controller
{
    /* Méthode d'affichage des formulaire d'erreur : */
    public function suggestAction($playerProposal_id, Request $request)
    {
        $repositoryPlayerProposal = $this->getDoctrine()->getManager()->getRepository('CLICHESPlayerBundle:PlayerProposal');
        $playerProposal = $repositoryPlayerProposal->findOneById($playerProposal_id);
        
        if ($playerProposal === null) {throw $this->createNotFoundException('Session : [id='.$playerProposal_id.'] inexistante.');}

        //On récupère les sources liées à l'entité :
        $entityservice = $this->container->get('data_data.entity');
        $sources = $entityservice->getSources($playerProposal->getPlayerOeuvre()->getView()->getEntity());

        $playerSuggest = new PlayerSuggest();
        $playerSuggest->setView($playerProposal->getPlayerOeuvre()->getView());
        $playerSuggest->setPlayerSuggestTraitement(false);
        if($this->getUser() != null) {$playerSuggest->setCreateUser($this->getUser());}
        $playerSuggest->setIpCreateUser($this->container->get('request')->getClientIp());
        
        $form = $this->createForm(new PlayerSuggestType(), $playerSuggest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($playerSuggest);
            $em->flush();

            return $this->redirectToRoute('cliches_player_selection_selection', array('playerSession_id' => $playerProposal->getPlayerOeuvre()->getPlayerSession()->getId()));
        }
        
        return $this->render('CLICHESPlayerBundle:Result:result.html.twig', array(
                                'isSuggestEnriched' => false,
                                'isSuggest' => true,
                                'isVote' => false,
                                'playerProposal' => $playerProposal,
                                'form' => $form->createView(),
                                'sources' => $sources
                            ));
    }
}
