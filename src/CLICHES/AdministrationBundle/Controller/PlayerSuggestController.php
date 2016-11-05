<?php

namespace CLICHES\AdministrationBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerSuggest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlayerSuggestController extends Controller
{
    public function indexAction()
    {
        $repositoryPlayerSuggest = $this->getDoctrine()->getManager()->getRepository('CLICHESPlayerBundle:PlayerSuggest');
        $playerSuggests = $repositoryPlayerSuggest->findBy(array('playerSuggestTraitement' => false), array('createDate' => 'ASC'));

        $repositoryUnrelevant = $this->getDoctrine()->getManager()->getRepository('DATADataBundle:UnrelevantField');
        $unrelevantFields = $repositoryUnrelevant->findByConfirmed(false);

        return $this->render('CLICHESAdministrationBundle:PlayerSuggest:index.html.twig', array(
            'playerSuggests' => $playerSuggests,
            'unrelevantFields' => $unrelevantFields
        ));
    }


    public function archivesAction()
    {
        $repositoryPlayerSuggest = $this->getDoctrine()->getManager()->getRepository('CLICHESPlayerBundle:PlayerSuggest');
        $playerSuggests = $repositoryPlayerSuggest->findBy(array('playerSuggestTraitement' => true), array('createDate' => 'ASC'));

        $repositoryUnrelevant = $this->getDoctrine()->getManager()->getRepository('DATADataBundle:UnrelevantField');
        $unrelevantFields = $repositoryUnrelevant->findByConfirmed(true);

        return $this->render('CLICHESAdministrationBundle:PlayerSuggest:archives.html.twig', array(
            'playerSuggests' => $playerSuggests,
            'unrelevantFields' => $unrelevantFields
        ));
    }

    public function playerSuggestTraitementAction($playerSuggest_id)
    {
        $repositoryPlayerSuggest = $this->getDoctrine()->getManager()
                                      ->getRepository('CLICHESPlayerBundle:PlayerSuggest');
        $playerSuggest = $repositoryPlayerSuggest->findOneById($playerSuggest_id);
        
        if ($playerSuggest === null) {
          throw $this->createNotFoundException('PlayerSuggest : [id='.$playerSuggest_id.'] inexistant.');
        }
        
        $playerSuggestAction = $this->container->get('cliches_player.playersuggestaction');
        $playerSuggestAction->traitementPlayerSuggest($playerSuggest);
             
        $this->get('session')->getFlashBag()->add('notice', 'PlayerSuggest a bien été notifié comme traité.' );
        return $this->redirectToRoute('cliches_administration_playersuggest_index');
    }


    public function playerSuggestValidateAction($playerSuggest_id)
    {
        $repositoryPlayerSuggest = $this->getDoctrine()->getManager()->getRepository('CLICHESPlayerBundle:PlayerSuggest');
        $playerSuggest = $repositoryPlayerSuggest->findOneById($playerSuggest_id);

        if ($playerSuggest === null) {
            throw $this->createNotFoundException('PlayerSuggest : [id='.$playerSuggest_id.'] inexistant.');
        }

        $playerSuggestAction = $this->container->get('cliches_player.playersuggestaction');
        $playerSuggestAction->validateSuggestField($playerSuggest);

        $this->get('session')->getFlashBag()->add('notice', 'PlayerSuggest a bien été notifié comme validé.' );
        return $this->redirectToRoute('cliches_administration_playersuggest_index');
    }


    public function playerSuggestRefuseAction($playerSuggest_id)
    {
        $repositoryPlayerSuggest = $this->getDoctrine()->getManager()->getRepository('CLICHESPlayerBundle:PlayerSuggest');
        $playerSuggest = $repositoryPlayerSuggest->findOneById($playerSuggest_id);

        if ($playerSuggest === null) {
            throw $this->createNotFoundException('PlayerSuggest : [id='.$playerSuggest_id.'] inexistant.');
        }

        $playerSuggestAction = $this->container->get('cliches_player.playersuggestaction');
        $playerSuggestAction->refuseSuggestField($playerSuggest);

        $this->get('session')->getFlashBag()->add('notice', 'PlayerSuggest a bien été notifié comme validé.' );
        return $this->redirectToRoute('cliches_administration_playersuggest_index');
    }
}
