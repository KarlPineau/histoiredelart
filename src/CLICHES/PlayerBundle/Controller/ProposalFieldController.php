<?php

namespace CLICHES\PlayerBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerProposal;
use CLICHES\PlayerBundle\Form\PlayerProposalLoadFieldType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProposalFieldController extends Controller
{
    public function proposalFieldAction($playerOeuvre_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $playerOeuvre = $repositoryPlayerOeuvre->findOneById($playerOeuvre_id);
        if ($playerOeuvre === null) {throw $this->createNotFoundException('Session : [id='.$playerOeuvre_id.'] inexistante.');}

        $playerProposal = new PlayerProposal();
        $playerProposal->setPlayerOeuvre($playerOeuvre);

        $repositoryImage = $em->getRepository('DATAImageBundle:Image');
        $image = $repositoryImage->findOneByView($playerProposal->getPlayerOeuvre()->getView());

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->addMeta('property', 'og:title', 'Clichés!')
            ->addMeta('property', 'og:description', 'Aidez-moi à reconnaitre ce cliché !')
            ->addMeta('property', 'og:image', $this->get('templating.helper.assets')->getUrl('uploads/gallery/'.$image->getFileImage()->getImageName()))
        ;

        $em = $this->getDoctrine()->getManager();

        //Si il existe déjà un playerProposal pour ce playerOeuvre (autrement dit on a déjà soumis un resultat), on bloque l'affichage du formulaire et on redirige vers le résultat déjà soumis
        $playerProposalCheck = $em->getRepository('CLICHESPlayerBundle:PlayerProposal')->findOneByPlayerOeuvre($playerOeuvre);
        if($playerProposalCheck != null) {
            return $this->redirect($this->generateUrl('cliches_player_result_result', array('playerProposal_id' => $playerProposal->getId())));
        }

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

    public function getFieldsAjaxAction($idImg)
    {
        $request = $this->get('request');
        if($request->isXmlHttpRequest())
        {
            return new Response($this->get('cliches_player.playerproposalfieldaction')->foundFields($idImg));
        }
            
    }
}
