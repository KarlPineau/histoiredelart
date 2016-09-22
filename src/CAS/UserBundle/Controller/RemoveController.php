<?php

namespace CAS\UserBundle\Controller;

use CAS\UserBundle\Form\Type\RemoveFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RemoveController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($this->getUser() == null) {throw $this->createAccessDeniedException("Cette page requière une authentification.");}
        $email = $this->getUser()->getEmail();

        $form = $this->createForm(new RemoveFormType(), $this->getUser());
        $form->handleRequest($request);

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            if($encoder->isPasswordValid($this->getUser()->getPassword(),$form->get('password')->getData(),$this->getUser()->getSalt())) {
                $this->get('session')->clear();
                $this->get('cas_user.remove')->remove($this->getUser());
                $em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Au revoir !')
                    ->setFrom('cliches@histoiredelart.fr')
                    ->setTo($email)
                    ->setBody($this->renderView('CASUserBundle:Remove:mail.html.twig'),'text/html')
                ;
                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add('notice', 'Votre compte a bien été supprimé');
                return $this->redirect($this->generateUrl('home_home_home_index'));
            } else {
                return $this->render('CASUserBundle:Remove:remove.html.twig', array(
                    'status' => 'wrong',
                    'form' => $form->createView(),
                ));
            }
        }

        return $this->render('CASUserBundle:Remove:remove.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
