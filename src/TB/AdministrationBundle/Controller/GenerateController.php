<?php

namespace TB\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TB\ModelBundle\Entity\TestedGame;
use TB\ModelBundle\Form\TestedGameType;

class GenerateController extends Controller
{
    public function generateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = new TestedGame();
        $testedGame->setIsRandomized(false);
        $testedGame->setCreateUser($this->getUser());

        $form = $this->createForm(new TestedGameType(), $testedGame);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                foreach ($testedGame->getTestedItems() as $testedItem) {
                    $testedItem->setTestedGame($testedGame);
                    $em->persist($testedItem);
                }
                $em->persist($testedGame);
                $em->flush();

                return $this->redirect($this->generateUrl('tb_administration_view_view', array('id' => $testedGame->getId())));
            }
        }
        return $this->render('TBAdministrationBundle:Generate:generate.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
