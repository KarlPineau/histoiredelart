<?php

namespace CAS\UserBundle\Controller;

use CAS\UserBundle\Form\UserPreferencesClichesType;
use CAS\UserBundle\Form\UserPreferencesMailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserPreferencesController extends Controller
{
    public function mailAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userPrefenences = $em->getRepository('CASUserBundle:UserPreferences')->findOneByUser($this->getUser());
        if ($userPrefenences === null) {throw $this->createNotFoundException('Oups ... Problème interne, revenez plus tard :)');}
        
        $form = $this->createForm(new UserPreferencesMailType(), $userPrefenences);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($userPrefenences);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, vos préférences mail ont bien été mises à jour.' );
            return $this->redirectToRoute('fos_user_profile_show');
        }
        
        return $this->render('CASUserBundle:UserPreferences:Mail/edit.html.twig', array(
                                'form' => $form->createView(),
                            ));
    }

    public function clichesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userPrefenences = $em->getRepository('CASUserBundle:UserPreferences')->findOneByUser($this->getUser());
        if ($userPrefenences === null) {throw $this->createNotFoundException('Oups ... Problème interne, revenez plus tard :)');}

        $form = $this->createForm(new UserPreferencesClichesType(), $userPrefenences);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($userPrefenences);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, vos préférences Clichés! ont bien été mises à jour.' );
            return $this->redirectToRoute('fos_user_profile_show');
        }

        return $this->render('CASUserBundle:UserPreferences:Cliches/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
