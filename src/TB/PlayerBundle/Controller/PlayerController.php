<?php

namespace TB\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlayerController extends Controller
{
    public function indexAction($testedGame_id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if ($testedGame === null) {throw $this->createNotFoundException('TestedGame : [id='.$testedGame_id.'] inexistant.');}

        return $this->render('TBPlayerBundle:Player:index.html.twig', array(
            'testedGame' => $testedGame
        ));
    }
}
