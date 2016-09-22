<?php

namespace DATA\TeachingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DATA\TeachingBundle\Entity\University;
use DATA\TeachingBundle\Form\UniversityEditType;
use DATA\TeachingBundle\Form\UniversityRegisterType;
use Symfony\Component\HttpFoundation\Request;

class UniversityController extends Controller
{
    public function indexAction()
    {
        $universities = $this->getDoctrine()->getManager()->getRepository('DATATeachingBundle:University')->findAll();
        
        return $this->render('DATATeachingBundle:University:index.html.twig', array(
                'universities' => $universities
                ));
    }
    
    public function listAction()
    {
        $listUniversities = $this->getDoctrine()->getManager()->getRepository('DATATeachingBundle:University')->findAll();

        return $this->render('DATATeachingBundle:University:list.html.twig', array('listUniversities' => $listUniversities));
    }
    
    public function viewAction($slug)
    {
        $university = $this->getDoctrine()->getManager()->getRepository('DATATeachingBundle:University')->findOneBySlug($slug);
        if ($university === null) {throw $this->createNotFoundException('Université : [slug='.$slug.'] inexistante.');}

        $universityAction = $this->container->get('data_teaching.university');
        $teachings = $universityAction->getTeachings($university);

        return $this->render('DATATeachingBundle:University:view.html.twig', array(
            'university' => $university,
            'teachings' => $teachings));
    }
    
    public function registerAction(Request $request)
    {
        $university = new University;
        
        $form = $this->createForm(new UniversityRegisterType, $university);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $university->setCreateUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($university);
            $em->flush();
                    
            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, l\'université a bien été créée.' );
            return $this->redirect($this->generateUrl('data_teaching_university_view', array('slug' => $university->getSlug())));
        }

        return $this->render('DATATeachingBundle:University:register.html.twig', array(
                                'form' => $form->createView(),
                            ));
    }
    
    public function editAction($slug, Request $request)
    {
        $university = $this->getDoctrine()->getManager()->getRepository('DATATeachingBundle:University')->findOneBySlug($slug);
        if ($university === null) {throw $this->createNotFoundException('Université : [slug='.$slug.'] inexistante.');}
        
        $form = $this->createForm(new UniversityEditType, $university);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $university->setUpdateUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($university);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, votre université a bien été éditée.' );
            return $this->redirect($this->generateUrl('data_teaching_university_view', array('slug' => $university->getSlug())));
        }
        
        return $this->render('DATATeachingBundle:University:edit.html.twig', array(
                                'university' => $university,
                                'form' => $form->createView(),
                            ));
    }
    
    public function deleteAction($slug)
    {
        $university = $this->getDoctrine()->getManager()->getRepository('DATATeachingBundle:University')->findOneBySlug($slug);
        if ($university === null) {throw $this->createNotFoundException('Enseignement : [slug='.$slug.'] inexistant.');}
        
        $universityAction = $this->container->get('data_teaching.university');
        $universityAction->deleteUniversity($university);
             
        $this->get('session')->getFlashBag()->add('notice', 'Votre université a bien été supprimée.' );
        return $this->forward('DATATeachingBundle:University:index');
    }
}
