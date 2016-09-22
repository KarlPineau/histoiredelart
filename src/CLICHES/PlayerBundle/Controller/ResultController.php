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
    public function resultAction($playerProposal_id, $validation=null, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPlayerProposal = $em->getRepository('CLICHESPlayerBundle:PlayerProposal');
        $playerProposal = $repositoryPlayerProposal->findOneById($playerProposal_id);
        $entityservice = $this->container->get('data_data.entity');

        if ($playerProposal === null) { throw $this->createNotFoundException('Session : [id='.$playerProposal_id.'] inexistante.'); }
        $fieldSuggest = $entityservice->suggestField($playerProposal->getPlayerOeuvre()->getView());

        $repositoryImage = $em->getRepository('DATAImageBundle:Image');
        $image = $repositoryImage->findOneByView($playerProposal->getPlayerOeuvre()->getView());
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->addMeta('property', 'og:title', 'Clichés!')
            ->addMeta('property', 'og:description', 'Aidez-moi à reconnaitre ce cliché !')
            ->addMeta('property', 'og:image', $this->get('templating.helper.assets')->getUrl('uploads/gallery/'.$image->getFileImage()->getImageName()))
        ;

        // Dans le cas où on valide un formulaire, on reprend la bonne fonction :
        if($validation != null) {
            if($validation == 'vote') {
                return $this->resultTeachingTestVote($playerProposal, null, $request);
            } elseif($validation == 'suggest') {
                return $this->resultWithSuggest($fieldSuggest, $playerProposal, null, $request);
            }
        } else { // Sinon, on tire au hasard :
            $random = rand(0, 1);
            $fieldSuggestBoolean = false; $voteBoolean = false;
            if($random == 0) { //On génère un field suggest
                if($fieldSuggest != null) {$fieldSuggestBoolean = true;}
            } elseif($random == 1) {
                if($this->getUser() != null and
                    $this->container->get('data_teaching.teachingtestvote')->checkVote($playerProposal->getPlayerOeuvre()->getPlayerSession()->getTeaching(),
                                                                                       $playerProposal->getPlayerOeuvre()->getView(),
                                                                                       $this->getUser()) == null)
                { $voteBoolean = true; }
                elseif($this->getUser() == null) {
                    $voteBoolean = true;
                }
            }

            //On récupère les sources liées à l'entité :
            $sources = $entityservice->getSources($playerProposal->getPlayerOeuvre()->getView()->getEntity());
            
            if($fieldSuggestBoolean == true) {
                return $this->resultWithSuggest($fieldSuggest, $playerProposal, $sources, $request);
            } elseif($voteBoolean == true) {
                return $this->resultTeachingTestVote($playerProposal, $sources, $request);
            } else
            {
                return $this->render('CLICHESPlayerBundle:Result:result.html.twig', array(
                    'isSuggest' => false,
                    'isVote' => false,
                    'isError' => false,
                    'playerProposal' => $playerProposal,
                    'sources' => $sources
                ));
            }
        }
    }

    public function resultWithSuggest($fieldSuggest, $playerProposal, $sources=null, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $playerSuggest = new PlayerSuggest;
        $playerSuggest->setView($playerProposal->getPlayerOeuvre()->getView());
        if($this->getUser() != null) {$playerSuggest->setCreateUser($this->getUser());}
        $playerSuggest->setIpCreateUser($this->container->get('request')->getClientIp());

        $playerResultservice = $this->container->get('cliches_player.playerresultaction');
        $fieldSuggestLabel = $playerResultservice->getLabel($fieldSuggest);
        $playerSuggest->setPlayerSuggestField($fieldSuggest);
        $playerSuggest->setPlayerSuggestTraitement(false);

        $form = $this->createForm(new PlayerSuggestEnrichedType(), $playerSuggest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($playerSuggest->getPlayerSuggestContent())) {
                $em->persist($playerSuggest);
                $em->flush();
            } elseif ($form->get('unrelevantField')->getData() == true) {
                $this->container->get('data_data.unrelevantfield')->setUnrelevant(
                    $playerSuggest->getPlayerSuggestField(),
                    NULL,
                    $playerSuggest->getView(),
                    false,
                    $this->getUser(),
                    $this->container->get('request')->getClientIp()
                );
            }

            return $this->redirect($this->generateUrl('cliches_player_selection_selection', array('playerSession_id' => $playerProposal->getPlayerOeuvre()->getPlayerSession()->getId())));
        }

        return $this->render('CLICHESPlayerBundle:Result:result.html.twig', array(
            'isSuggestEnriched' => true,
            'isVote' => false,
            'isSuggest' => false,
            'form' => $form->createView(),
            'fieldSuggest' => $fieldSuggest,
            'fieldSuggestLabel' => $fieldSuggestLabel,
            'playerProposal' => $playerProposal,
            'sources' => $sources
        ));
    }

    public function resultTeachingTestVote($playerProposal, $sources=null, Request $request)
    {
        //Définition des variables :
        $em = $this->getDoctrine()->getManager();
        $teaching = $playerProposal->getPlayerOeuvre()->getPlayerSession()->getTeaching();

        //Définition du TeachingTest
        if($em->getRepository('DATATeachingBundle:TeachingTest')->findOneBy(array('teaching' => $teaching, 'view' => $playerProposal->getPlayerOeuvre()->getView())) == null) {
            $teachingTest = new TeachingTest();
            $teachingTest->setTeaching($teaching);
            $teachingTest->setView($playerProposal->getPlayerOeuvre()->getView());
            $em->persist($teachingTest);
            $em->flush();
        } else {
            $teachingTest = $em->getRepository('DATATeachingBundle:TeachingTest')->findOneBy(array('teaching' => $teaching, 'view' => $playerProposal->getPlayerOeuvre()->getView()));
        }
        // END définition TeachingTest

        // Lancement TeachingTestVote
        $teachingTestVote = new TeachingTestVote();
        $teachingTestVote->setTeachingTest($teachingTest);
        if($this->getUser() != null) {$teachingTestVote->setCreateUser($this->getUser());}

        $form = $this->createForm(new TeachingTestVoteType(), $teachingTestVote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($teachingTestVote->getVote() !== null) {
                $em->persist($teachingTestVote);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('cliches_player_selection_selection', array('playerSession_id' => $playerProposal->getPlayerOeuvre()->getPlayerSession()->getId())));
        }

        return $this->render('CLICHESPlayerBundle:Result:result.html.twig', array(
            'isSuggestEnriched' => false,
            'isVote' => true,
            'isSuggest' => false,
            'form' => $form->createView(),
            'playerProposal' => $playerProposal,
            'sources' => $sources
        ));
    }

    /* -- Fonction retournant les champs sous format JSON -- */
    public function resultsAction($playerProposal_id)
    {
        $request = $this->get('request');
        if($request->isXmlHttpRequest())
        {
            return new Response($this->get('cliches_player.playerresultaction')->foundResults($playerProposal_id));
        }
    }
}
