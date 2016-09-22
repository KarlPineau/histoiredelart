<?php

namespace CLICHES\PlayerBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerEndViews;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EndController extends Controller
{
    /* Méthode d'affichage des oeuvres : */
    public function endAction($playerSession_id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPlayerSession = $em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $playerSession = $repositoryPlayerSession->findOneById($playerSession_id);
        
        if ($playerSession === null) {
            throw $this->createNotFoundException('Session : [id='.$playerSession_id.'] inexistante.');
        }
        $playerSession->setDateEnd(new \DateTime("now"));
        $em->persist($playerSession);

        $playerEndView = new PlayerEndViews();
        $playerEndView->setPlayerSession($playerSession);
        $em->persist($playerEndView);
        
        $em->flush();

        $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $playerOeuvres = $repositoryPlayerOeuvre->findByPlayerSession($playerSession);

        $playerOeuvresCollection = array();
        foreach($playerOeuvres as $key => $playerOeuvre) {
            $repositoryPlayerProposal = $em->getRepository('CLICHESPlayerBundle:PlayerProposal');
            $playerProposal = $repositoryPlayerProposal->findOneByPlayerOeuvre($playerOeuvre);

            $servicePlayerResult = $this->container->get('cliches_player.playerResultAction');

            if($playerProposal != null AND $servicePlayerResult->testFilled($playerProposal) == true) {
                $playerOeuvresCollection[$key] = ['playerOeuvre' => $playerOeuvre,
                    'playerProposal' => $servicePlayerResult->foundResults($playerProposal->getId(), false)];
            } else {
                $playerOeuvresCollection[$key] = ['playerOeuvre' => $playerOeuvre,
                    'playerProposal' => null];
            }
        }


        return $this->render('CLICHESPlayerBundle:End:endGame.html.twig', array(
                        'playerOeuvres' => $playerOeuvresCollection,
        ));
    }
}
