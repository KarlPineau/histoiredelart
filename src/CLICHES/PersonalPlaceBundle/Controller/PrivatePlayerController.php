<?php

namespace CLICHES\PersonalPlaceBundle\Controller;

use CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer;
use CLICHES\PersonalPlaceBundle\Form\PrivatePlayerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PrivatePlayerController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $privatePlayers = $em->getRepository('CLICHESPersonalPlaceBundle:PrivatePlayer')->findBy(array('createUser' => $this->getUser()));

        return $this->render('CLICHESPersonalPlaceBundle:PrivatePlayer:index.html.twig', array('privatePlayers' => $privatePlayers));
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $privatePlayer = $em->getRepository('CLICHESPersonalPlaceBundle:PrivatePlayer')->findOneBy(array('id' => $id, 'createUser' => $this->getUser()));
        if($privatePlayer === null) {throw $this->createNotFoundException('Cet identifiant ('.$id.') n\'est pas défini');}

        return $this->render('CLICHESPersonalPlaceBundle:PrivatePlayer:view.html.twig', array('privatePlayer' => $privatePlayer));
    }

    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $privatePlayer = new PrivatePlayer();
        $privatePlayer->setIpCreateUser($this->container->get('request')->getClientIp());
        if($this->getUser() != null) {$privatePlayer->setCreateUser($this->getUser());}

        $form = $this->createForm(new PrivatePlayerType(), $privatePlayer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $privatePlayer->setCountPlayer(0);
            $em->persist($privatePlayer);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, votre session a bien été générée.' );
            return $this->redirect($this->generateUrl('cliches_personalplace_privateplayer_end', array('id' => $privatePlayer->getId())));
        }

        return $this->render('CLICHESPersonalPlaceBundle:PrivatePlayer:Register/register.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function endAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $privatePlayer = $em->getRepository('CLICHESPersonalPlaceBundle:PrivatePlayer')->findOneBy(array('id' => $id, 'createUser' => $this->getUser()));
        if($privatePlayer === null) {throw $this->createNotFoundException('Cet identifiant ('.$id.') n\'est pas défini');}

        return $this->render('CLICHESPersonalPlaceBundle:PrivatePlayer:end.html.twig', array('privatePlayer' => $privatePlayer));
    }
}
