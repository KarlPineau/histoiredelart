<?php

namespace CLICHES\AdministrationBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerSuggest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlayerSuggestController extends Controller
{
    public function transitionAction()
    {
        set_time_limit(0);
        $em = $this->getDoctrine()->getManager();
        $count = 0;

        foreach($em->getRepository('CLICHESPlayerBundle:PlayerError')->findAll() as $playerError) {
            $playerSuggest = new PlayerSuggest();
            $playerSuggest->setCreateUser($playerError->getCreateUser());
            $playerSuggest->setIpCreateUser($playerError->getIpCreateUser());
            $playerSuggest->setCreateDate($playerError->getCreateDate());
            $playerSuggest->setPlayerSuggestAccept($playerError->getPlayerErrorAccept());
            $playerSuggest->setPlayerSuggestAcceptExplain($playerError->getPlayerErrorAcceptExplain());
            $playerSuggest->setPlayerSuggestContent($playerError->getPlayerErrorComment());
            $playerSuggest->setPlayerSuggestField(null);
            $playerSuggest->setPlayerSuggestTraitement($playerError->getPlayerErrorTraitement());
            $playerSuggest->setView($playerError->getPlayerOeuvre()->getView());
            $em->persist($playerSuggest);
            $em->remove($playerError);
            $count++;
        }
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', $count.' PlayerError migrés.');
        return $this->redirectToRoute('cliches_administration_playersuggest_index');
    }

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
