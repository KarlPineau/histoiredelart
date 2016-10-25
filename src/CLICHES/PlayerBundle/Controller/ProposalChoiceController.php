<?php

namespace CLICHES\PlayerBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerProposal;
use CLICHES\PlayerBundle\Entity\PlayerProposalChoice;
use CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue;
use CLICHES\PlayerBundle\Form\PlayerProposalLoadChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProposalChoiceController extends Controller
{
    public function shuffleAssoc($list) {
        if (!is_array($list)) return $list;

        $keys = array_keys($list);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }
        return $random;
    }

    public function proposalChoiceAction($playerOeuvre_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $playerOeuvre = $repositoryPlayerOeuvre->findOneById($playerOeuvre_id);
        if ($playerOeuvre === null) {throw $this->createNotFoundException('Session : [id='.$playerOeuvre_id.'] inexistante.');}

        $image = $em->getRepository('DATAImageBundle:Image')->findOneByView($playerOeuvre->getView());
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->addMeta('property', 'og:title', 'Clichés!')
            ->addMeta('property', 'og:description', 'Aidez-moi à reconnaitre ce cliché !')
            ->addMeta('property', 'og:image', $this->get('templating.helper.assets')->getUrl('uploads/gallery/'.$image->getFileImage()->getImageName()))
        ;

        $repositoryPlayerProposal = $em->getRepository('CLICHESPlayerBundle:PlayerProposal');
        if($repositoryPlayerProposal->findOneByPlayerOeuvre($playerOeuvre) == null) {
            return $this->generateNewPlayerProposalChoice($playerOeuvre, $request);
        } else {
            $playerProposal = $repositoryPlayerProposal->findOneByPlayerOeuvre($playerOeuvre);

            $listChoices = array();

            foreach($em->getRepository('CLICHESPlayerBundle:PlayerProposalChoice')->findByPlayerProposal($playerProposal) as $playerProposalChoice) {
                $arrayValues = [];
                foreach($em->getRepository('CLICHESPlayerBundle:PlayerProposalChoiceValue')->findByPlayerProposalChoice($playerProposalChoice) as $choiceValue) {
                    $arrayValues[$choiceValue->getId()] = $choiceValue->getValue();
                }
                $arrayValues = $this->shuffleAssoc($arrayValues);
                $listChoices[$playerProposalChoice->getField()] = $arrayValues;
            }

            $form = $this->createForm(new PlayerProposalLoadChoiceType(), $playerProposal, array(
                'attr' => array('listChoices' => $listChoices)));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                return $this->validationResults($playerProposal, $form);
            } else {
                return $this->reloadPlayerProposalChoice($playerProposal, $form);
            }
        }
    }

    public function generateNewPlayerProposalChoice($playerOeuvre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $playerProposal = new PlayerProposal();
        $playerProposal->setPlayerOeuvre($playerOeuvre);

        $image = $em->getRepository('DATAImageBundle:Image')->findOneByView($playerProposal->getPlayerOeuvre()->getView());

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

        $em->persist($playerProposal);
        $listChoicesRebuilt = array();
        foreach($listChoices as $field => $choiceContainer) {
            $playerProposalChoice = new PlayerProposalChoice();
            $playerProposalChoice->setPlayerProposal($playerProposal);
            $playerProposalChoice->setField($field);
            $em->persist($playerProposalChoice);
            $listChoicesRebuiltContent = array();
            $fieldChoice = '';

            foreach($choiceContainer as $choice) {
                $playerProposalChoiceValue = new PlayerProposalChoiceValue();
                $playerProposalChoiceValue->setEntity($choice['entity']);
                $playerProposalChoiceValue->setField($field);
                $playerProposalChoiceValue->setValue($choice['value']);
                $playerProposalChoiceValue->setIsTrue($choice['quizz']);
                $playerProposalChoiceValue->setPlayerProposalChoice($playerProposalChoice);
                $em->persist($playerProposalChoiceValue);
                $em->flush();

                $listChoicesRebuiltContent[$playerProposalChoiceValue->getId()] = $choice['value'];
                $fieldChoice = $field;
            }
            $listChoicesRebuiltContent = $this->shuffleAssoc($listChoicesRebuiltContent);
            $listChoicesRebuilt[$fieldChoice] = $listChoicesRebuiltContent;
        }


        $form = $this->createForm(new PlayerProposalLoadChoiceType(), $playerProposal, array(
            'attr' => array('listChoices' => $listChoicesRebuilt)));
        $form->handleRequest($request);

        return $this->render('CLICHESPlayerBundle:Proposal:proposal.html.twig', array(
            'proposalType' => 'choice',
            'form' => $form->createView(),
            'playerOeuvre' => $playerOeuvre,
            'playerProposal' => $playerProposal,
            'image' => $image
        ));
    }

    public function reloadPlayerProposalChoice($playerProposal, $form)
    {
        $em = $this->getDoctrine()->getManager();
        $playerOeuvre = $playerProposal->getPlayerOeuvre();
        $image = $em->getRepository('DATAImageBundle:Image')->findOneByView($playerOeuvre->getView());

        return $this->render('CLICHESPlayerBundle:Proposal:proposal.html.twig', array(
            'proposalType' => 'choice',
            'form' => $form->createView(),
            'playerOeuvre' => $playerOeuvre,
            'playerProposal' => $playerProposal,
            'image' => $image
        ));
    }

    public function validationResults($playerProposal, $form)
    {
        $em = $this->getDoctrine()->getManager();
        foreach($em->getRepository('CLICHESPlayerBundle:PlayerProposalChoice')->findByPlayerProposal($playerProposal) as $playerProposalChoice) {
            $playerProposalChoiceValue = $em->getRepository('CLICHESPlayerBundle:PlayerProposalChoiceValue')->findOneById($form->get($playerProposalChoice->getField())->getData());
            $playerProposalChoice->setPlayerProposalChoiceValueSelected($playerProposalChoiceValue);
            $em->persist($playerProposalChoice);
        }
        $em->persist($playerProposal);
        $em->flush();

        return $this->redirect($this->generateUrl('cliches_player_result_result', array('playerProposal_id' => $playerProposal->getId())));
    }

    public function getFieldsAjaxAction($field)
    {
        $request = $this->get('request');
        if($request->isXmlHttpRequest())
        {
            return new Response(json_encode($this->get('cliches_player.playerresultaction')->getLabel($field)));
        }

    }
}
