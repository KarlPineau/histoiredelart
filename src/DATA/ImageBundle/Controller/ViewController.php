<?php

namespace DATA\ImageBundle\Controller;

use DATA\ImageBundle\Form\ViewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ViewController extends Controller
{
    public function editAction($view_id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryView = $em->getRepository('DATAImageBundle:View');
        $repositoryImage = $em->getRepository('DATAImageBundle:Image');

        $view = $repositoryView->findOneById($view_id);
        if ($view === null) { throw $this->createNotFoundException('View : [id='.$view_id.'] inexistante.'); }
        $image = $repositoryImage->findOneByView($view);
        if ($image === null) { throw $this->createNotFoundException('Image for view : [id='.$view_id.'] inexistante.'); }
        
        $form = $this->createForm(new ViewType(), $view);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($view);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Félicitations, la vue a bien été modifiée.' );
                return $this->redirectToRoute('data_data_entity_view', array('id' => $view->getEntity()->getId()));
            }
        }
        return $this->render('DATAImageBundle:View:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function deleteAction($view_id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryView = $em->getRepository('DATAImageBundle:View');
        $view = $repositoryView->findOneById($view_id);
        if ($view === null) { throw $this->createNotFoundException('View : [id='.$view_id.'] inexistante.'); }
        $entity = $view->getEntity();
        $this->container->get('data_image.view_action')->deleteView($view);

        $this->get('session')->getFlashBag()->add('notice', 'Félicitations, la vue a bien été supprimée.' );
        return $this->redirectToRoute('data_data_entity_view', array('id' => $entity->getId()));
    }
}
