<?php

namespace TB\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TB\ModelBundle\Entity\TestedGame;
use TB\ModelBundle\Form\TestedGameAddItemsType;
use TB\ModelBundle\Form\TestedGameEditType;
use TB\ModelBundle\Form\TestedGameIconEditType;
use TB\ModelBundle\Form\TestedGameType;
use TB\ModelBundle\Form\TestedItemEditType;

class TestedGameController extends Controller
{
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = new TestedGame();
        $testedGame->setIsRandomized(false);
        $testedGame->setIsPrivate(false);
        $testedGame->setIsOnline(false);
        $testedGame->setIsOfficial(true);
        $testedGame->setCreateUser($this->getUser());

        $form = $this->createForm(new TestedGameType(), $testedGame);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($testedGame->getTestedItems() as $testedItem) {
                $testedItem->setTestedGame($testedGame);
                $em->persist($testedItem);
            }
            $em->persist($testedGame);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, votre partie a bien été éditée.' );
            return $this->redirect($this->generateUrl('tb_administration_testedgame_view', array('testedGame_id' => $testedGame->getId())));
        }
        return $this->render('TBAdministrationBundle:TestedGame:Create/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function addItemsAction($testedGame_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if($testedGame === null) {throw $this->createNotFoundException('No TestedGame for this id : '.$testedGame_id);}

        $form = $this->createForm(new TestedGameAddItemsType(), $testedGame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($testedGame->getTestedItems() as $testedItem) {
                $testedItem->setTestedGame($testedGame);
                $em->persist($testedItem);
            }
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, les modifications ont bien été enregistrées.' );
            return $this->redirect($this->generateUrl('tb_administration_testedgame_view', array('testedGame_id' => $testedGame->getId())));
        }
        return $this->render('TBAdministrationBundle:TestedGame:AddItems/addItems.html.twig', array(
            'testedGame' => $testedGame,
            'form' => $form->createView(),
        ));
    }

    public function editItemAction($testedGame_id, $testedItem_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if($testedGame === null) {throw $this->createNotFoundException('No TestedGame for this id : '.$testedGame_id);}
        $testedItem = $em->getRepository('TBModelBundle:TestedItem')->findOneById($testedItem_id);
        if($testedItem === null) {throw $this->createNotFoundException('No TestedItem for this id : '.$testedItem_id);}
        if($testedItem->getTestedGame() != $testedGame) {throw $this->createNotFoundException('Error! Unmatch TestedGame <> TestedItem');}

        $form = $this->createForm(new TestedItemEditType(), $testedItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($testedItem);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, les modifications ont bien été enregistrées.' );
            return $this->redirect($this->generateUrl('tb_administration_testedgame_view', array('testedGame_id' => $testedGame->getId())));
        }
        return $this->render('TBAdministrationBundle:TestedGame:EditItem/editItem.html.twig', array(
            'testedGame' => $testedGame,
            'testedItem' => $testedItem,
            'form' => $form->createView(),
        ));
    }

    public function removeItemAction($testedGame_id, $testedItem_id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if($testedGame === null) {throw $this->createNotFoundException('No TestedGame for this id : '.$testedGame_id);}
        $testedItem = $em->getRepository('TBModelBundle:TestedItem')->findOneById($testedItem_id);
        if($testedItem === null) {throw $this->createNotFoundException('No TestedItem for this id : '.$testedItem_id);}
        if($testedItem->getTestedGame() != $testedGame) {throw $this->createNotFoundException('Error! Unmatch TestedGame <> TestedItem');}

        foreach($em->getRepository('TBModelBundle:TestedItemResult')->findByTestedItem($testedItem) as $testedItemResult) {
            $em->remove($testedItemResult);
        }
        $testedGame->removeTestedItem($testedItem);
        $em->persist($testedGame);
        $em->remove($testedItem);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Félicitations, les modifications ont bien été enregistrées.' );
        return $this->redirect($this->generateUrl('tb_administration_testedgame_view', array('testedGame_id' => $testedGame->getId())));
    }

    public function editAction($testedGame_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if($testedGame === null) {throw $this->createNotFoundException('No TestedGame for this id : '.$testedGame_id);}

        $form = $this->createForm(new TestedGameEditType(), $testedGame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($testedGame);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, votre partie a bien été éditée.' );
            return $this->redirect($this->generateUrl('tb_administration_testedgame_view', array('testedGame_id' => $testedGame->getId())));
        }
        return $this->render('TBAdministrationBundle:TestedGame:Edit/edit.html.twig', array(
            'testedGame' => $testedGame,
            'form' => $form->createView(),
        ));
    }

    public function editIconAction($testedGame_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if($testedGame === null) {throw $this->createNotFoundException('No TestedGame for this id : '.$testedGame_id);}

        $form = $this->createForm(new TestedGameIconEditType(), $testedGame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($testedGame);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, votre partie a bien été éditée.' );
            return $this->redirect($this->generateUrl('tb_administration_testedgame_view', array('testedGame_id' => $testedGame->getId())));
        }
        return $this->render('TBAdministrationBundle:TestedGame:EditIcon/editIcon.html.twig', array(
            'testedGame' => $testedGame,
            'form' => $form->createView(),
        ));
    }

    public function viewAction($testedGame_id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);

        if($testedGame === null) {throw $this->createNotFoundException('TestedGame non trouvé :'.$testedGame_id);}

        return $this->render('TBAdministrationBundle:TestedGame:View/view.html.twig', array(
            'testedGame' => $testedGame,
        ));
    }

    public function updateOnlineStatusAction($testedGame_id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if($testedGame === null) {throw $this->createNotFoundException('TestedGame non trouvé :'.$testedGame_id);}

        if($testedGame->getIsOnline() == true) {
            $testedGame->setIsOnline(false);
        } else {
            $testedGame->setIsOnline(true);
        }

        $em->persist($testedGame);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Félicitations, le statut de votre partie a bien été modifié.' );
        return $this->redirectToRoute('tb_administration_testedgame_view', array('testedGame_id' => $testedGame_id));
    }

    public function updateOfficialStatusAction($testedGame_id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if($testedGame === null) {throw $this->createNotFoundException('TestedGame non trouvé :'.$testedGame_id);}

        if($testedGame->getIsOfficial() == true) {
            $testedGame->setIsOfficial(false);
        } else {
            $testedGame->setIsOfficial(true);
        }

        $em->persist($testedGame);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Félicitations, le statut de votre partie a bien été modifié.' );
        return $this->redirectToRoute('tb_administration_testedgame_view', array('testedGame_id' => $testedGame_id));
    }

    public function removeAction($testedGame_id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if($testedGame === null) {throw $this->createNotFoundException('TestedGame non trouvé :'.$testedGame_id);}

        $this->get('tb_model.testedgame')->remove($testedGame);

        $this->get('session')->getFlashBag()->add('notice', 'Félicitations, votre partie a bien été supprimée.' );
        return $this->redirectToRoute('tb_administration_home_index');
    }
}
