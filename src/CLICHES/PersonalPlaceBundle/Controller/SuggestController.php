<?php

namespace CLICHES\PersonalPlaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SuggestController extends Controller
{
    public function indexAction()
    {
        $current_user = $this->getUser();
        if($current_user != null) {
            $em = $this->getDoctrine()->getManager();
            $repositorySuggest = $em->getRepository('CLICHESPlayerBundle:PlayerSuggest');
            $playerSuggests = $repositorySuggest->findBy(array('createUser' => $current_user), array('createDate' => 'DESC'));
            $repositoryError = $em->getRepository('CLICHESPlayerBundle:PlayerError');
            $playerErrors = $repositoryError->findBy(array('createUser' => $current_user), array('createDate' => 'DESC'));

            $finalArray = array();
            foreach($playerSuggests as $playerSuggest) {
                $finalArray[] = [
                    'type' => 'playerSuggest',
                    'entity' => $playerSuggest
                ];
            }
            foreach($playerErrors as $playerError) {
                $finalArray[] = [
                    'type' => 'playerError',
                    'entity' => $playerError
                ];
            }

            return $this->render('CLICHESPersonalPlaceBundle:Suggest:index.html.twig', array(
                'playerModifications' => $finalArray,
            ));

        } else {
            throw $this->createNotFoundException('Oups ... Il faut être connecté pour accéder à cette page ...');
        }
    }
}
