<?php

namespace TB\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TB\ModelBundle\Entity\TestedGame;
use TB\ModelBundle\Form\TestedGameType;

class ViewController extends Controller
{
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($id);

        return $this->render('TBAdministrationBundle:View:index.html.twig', array(
            'testedGame' => $testedGame,
        ));
    }
}
