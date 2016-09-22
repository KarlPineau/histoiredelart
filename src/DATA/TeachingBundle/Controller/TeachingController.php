<?php

namespace DATA\TeachingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DATA\TeachingBundle\Entity\Teaching;
use DATA\TeachingBundle\Form\TeachingEditType;
use DATA\TeachingBundle\Form\TeachingRegisterType;
use Symfony\Component\HttpFoundation\Request;

class TeachingController extends Controller
{
    public function indexAction()
    {
        $teachings = $this->getDoctrine()->getManager()->getRepository('DATATeachingBundle:Teaching')->findAll();

        return $this->render('DATATeachingBundle:Teaching:index.html.twig', array(
                'teachings' => $teachings
                ));
    }
    
    public function listAction()
    {
        $listTeachings = $this->getDoctrine()->getManager()->getRepository('DATATeachingBundle:Teaching')->findAll();
        
        $teachingAction = $this->container->get('cliches_player.playersessionaction');
        $total = array();
        foreach ($listTeachings as $teaching) {
            $number = $teachingAction->countOeuvreByTeaching($teaching);
            array_push($total, $number);
        }
             
        return $this->render('DATATeachingBundle:Teaching:list.html.twig', array('total' => $total));
    }

    public function viewAction($slug, Request $request)
    {
        $teaching = $this->getDoctrine()->getManager()->getRepository('DATATeachingBundle:Teaching')->findOneBySlug($slug);
        if ($teaching === null) { throw $this->createNotFoundException('Enseignement : [slug='.$slug.'] inexistant.'); }

        $entities = $this->get('data_data.entity')->getByTeaching($teaching);
        $paginator  = $this->get('knp_paginator');
        $listEntities = $paginator->paginate(
            $entities,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );

        return $this->render('DATATeachingBundle:Teaching:view.html.twig', array(
            'teaching' => $teaching,
            'listEntities' => $listEntities
        ));
    }
    
    public function registerAction(Request $request)
    {
        $teaching = new Teaching;
        
        $form = $this->createForm(new TeachingRegisterType, $teaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teaching->setCreateUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($teaching);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, l\'enseignement a bien été créé.' );
            return $this->redirect($this->generateUrl('data_teaching_teaching_view', array('slug' => $teaching->getSlug())));
        }

        return $this->render('DATATeachingBundle:Teaching:register.html.twig', array(
                                'form' => $form->createView(),
                            ));
    }
    
    public function editAction($slug, Request $request)
    {
        $teaching = $this->getDoctrine()->getManager()->getRepository('DATATeachingBundle:Teaching')->findOneBySlug($slug);
        if ($teaching === null) {throw $this->createNotFoundException('Enseignement : [slug='.$slug.'] inexistant.');}
        
        $form = $this->createForm(new TeachingEditType, $teaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teaching->setUpdateUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($teaching);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, votre enseignement a bien été édité.' );
            return $this->redirect($this->generateUrl('data_teaching_teaching_view', array('slug' => $teaching->getSlug())));
        }
        
        return $this->render('DATATeachingBundle:Teaching:edit.html.twig', array(
                                'teaching' => $teaching,
                                'form' => $form->createView(),
                            ));
    }
    
    public function deleteAction($slug)
    {
        $teaching = $this->getDoctrine()->getManager()->getRepository('DATATeachingBundle:Teaching')->findOneBySlug($slug);
        if($teaching === null) {throw $this->createNotFoundException('Enseignement : [slug='.$slug.'] inexistant.');}
        
        $teachingAction = $this->container->get('data_teaching.teaching');
        $teachingAction->deleteTeaching($teaching);
             
        $this->get('session')->getFlashBag()->add('notice', 'Votre enseignement a bien été supprimé.' );
        return $this->forward('DATATeachingBundle:Teaching:index');
    }
}
