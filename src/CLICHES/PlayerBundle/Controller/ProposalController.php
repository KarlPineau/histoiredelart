<?php

namespace CLICHES\PlayerBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerProposalChoice;
use CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue;
use CLICHES\PlayerBundle\Form\PlayerProposalChoiceType;
use CLICHES\PlayerBundle\Form\PlayerProposalFieldType;
use CLICHES\PlayerBundle\Form\PlayerProposalLoadChoiceType;
use CLICHES\PlayerBundle\Form\PlayerProposalLoadFieldType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CLICHES\PlayerBundle\Entity\PlayerProposal;
use Symfony\Component\HttpFoundation\Request;

class ProposalController extends Controller
{

    /* 
     * Méthode affichant le formulaire principal du jeu : 
     * 
     * Fonctionnement de proposal :
     * Récupère une vue
     * Va chercher l'oeuvre
     * Liste les champs remplis de l'oeuvre
     * Créer pour chaque champ, un champ dans le formulaire en construction
     * 
     */
    public function proposalAction($playerOeuvre_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $playerOeuvre = $repositoryPlayerOeuvre->findOneById($playerOeuvre_id);
        
        if ($playerOeuvre === null) {throw $this->createNotFoundException('Session : [id='.$playerOeuvre_id.'] inexistante.');}

        //Si il existe déjà un playerProposal pour ce playerOeuvre (autrement dit on a déjà soumis un resultat),
        // on bloque l'affichage du formulaire et on redirige vers le résultat déjà soumis
        $playerProposal = $em->getRepository('CLICHESPlayerBundle:PlayerProposal')->findOneByPlayerOeuvre($playerOeuvre);
        if($playerProposal != null) {
            return $this->redirect($this->generateUrl('cliches_player_result_result', array('playerProposal_id' => $playerProposal->getId())));
        }
        
        //Sinon, on charge le formulaire adéquat
        if($playerOeuvre->getPlayerSession()->getSimpleSession() == false) {
            return $this->proposalNormalAction($playerOeuvre, $request);
        } elseif($playerOeuvre->getPlayerSession()->getSimpleSession() == true) {
            return $this->proposalSimpleAction($playerOeuvre);
        }
    }

    public function proposalNormalAction($playerOeuvre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $playerProposal = new PlayerProposal;
        $playerProposal->setPlayerOeuvre($playerOeuvre);

        $repositoryImage = $em->getRepository('DATAImageBundle:Image');
        $image = $repositoryImage->findOneByView($playerProposal->getPlayerOeuvre()->getView());

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->addMeta('property', 'og:title', 'Clichés!')
            ->addMeta('property', 'og:description', 'Aidez-moi à reconnaitre ce cliché !')
            ->addMeta('property', 'og:image', $this->get('templating.helper.assets')->getUrl('uploads/gallery/'.$image->getFileImage()->getImageName()))
        ;

        if($playerOeuvre->getPlayerSession()->getProposalType() == 'modeChoice') {
            return $this->proposalNormalChoiceAction($playerProposal, $playerOeuvre, $image, $request);
        } elseif($playerOeuvre->getPlayerSession()->getProposalType() == 'modeField') {
            return $this->proposalNormalFieldAction($playerProposal, $playerOeuvre, $image, $request);
        }
    }

    public function proposalNormalChoiceAction($playerProposal, $playerOeuvre, $image, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('data_data.entity')->getByView($playerOeuvre->getView());

        $listChoices = array();
        foreach($this->get('data_data.entity')->getListFieldsForEntity($entity) as $fieldContainer) {
            //Génération des choices :
            if($this->get('data_data.entity')->valueByField($fieldContainer['field'], $entity) != 'empty' AND
                $this->get('data_data.entity')->valueByField($fieldContainer['field'], $entity) != 'unrelevant' AND
                $this->get('data_data.entity')->valueByField($fieldContainer['field'], $entity) != 'noproperty') {
                $listChoices[$fieldContainer['field']] = $this->get('cliches_player.playerproposalchoiceaction')->getValuesAction($playerOeuvre, $fieldContainer['field'], 3);
            }
        }

        foreach($listChoices as $filed => $choiceContainer) {
            $playerProposalChoice = new PlayerProposalChoice();
            $playerProposalChoice->setPlayerProposal($playerProposal);

            foreach($choiceContainer as $choice) {
                $playerProposalChoiceValue = new PlayerProposalChoiceValue();
                $playerProposalChoiceValue->setEntity($choice['entity']);
                $playerProposalChoiceValue->setField($filed);
                $playerProposalChoiceValue->setValue($choice['value']);
                $playerProposalChoiceValue->setPlayerProposalChoice($playerProposalChoice);
                $em->persist($playerProposalChoiceValue);
            }

            $em->persist($playerProposalChoice);
        }

        $form = $this->createForm(new PlayerProposalLoadChoiceType(), $playerProposal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($playerProposal);
            $em->flush();

            return $this->redirect($this->generateUrl('cliches_player_result_result', array('playerProposal_id' => $playerProposal->getId())));
        }

        return $this->render('CLICHESPlayerBundle:Proposal:proposal.html.twig', array(
            'proposalType' => 'choice',
            'form' => $form->createView(),
            'playerOeuvre' => $playerOeuvre,
            'playerProposal' => $playerProposal,
            'image' => $image,
            'listChoices' => $listChoices
        ));
    }

    public function proposalNormalFieldAction($playerProposal, $playerOeuvre, $image, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new PlayerProposalLoadFieldType(), $playerProposal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $playerProposalFields = $playerProposal->getPlayerProposalFields();
            foreach ($playerProposalFields as $playerProposalField) {
                $playerProposalField->setPlayerProposal($playerProposal);
                $em->persist($playerProposalField);
            }

            $em->persist($playerProposal);
            $em->flush();

            return $this->redirect($this->generateUrl('cliches_player_result_result', array('playerProposal_id' => $playerProposal->getId())));
        }

        return $this->render('CLICHESPlayerBundle:Proposal:proposal.html.twig', array(
            'proposalType' => 'field',
            'form' => $form->createView(),
            'playerOeuvre' => $playerOeuvre,
            'playerProposal' => $playerProposal,
            'image' => $image
        ));
    }

    public function proposalSimpleAction($playerOeuvre)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryImage = $em->getRepository('DATAImageBundle:Image');
        $image = $repositoryImage->findOneByView($playerOeuvre->getView());

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->addMeta('property', 'og:title', 'Clichés!')
            ->addMeta('property', 'og:description', 'Aidez-moi à reconnaitre ce cliché !')
            ->addMeta('property', 'og:image', $this->get('templating.helper.assets')->getUrl('uploads/gallery/'.$image->getFileImage()->getImageName()))
        ;


        return $this->render('CLICHESPlayerBundle:Proposal:visualisation.html.twig', array(
            'playerSession' => $playerOeuvre->getPlayerSession(),
            'image' => $image,
            'view' => $playerOeuvre->getView()
        ));
    }
}
