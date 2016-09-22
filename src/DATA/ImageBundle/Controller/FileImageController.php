<?php

namespace DATA\ImageBundle\Controller;

use DATA\ImageBundle\Entity\FileImage;
use DATA\ImageBundle\Form\FileImageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileImageController extends Controller
{
    public function indexAction()
    {
        return $this->render('DATAImageBundle:FileImage:index.html.twig');
    }

    public function registerAction()
    {
        $fileImage = new FileImage();

        $form = $this->createForm(new FileImageType(), $fileImage);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($fileImage);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Félicitations, l\'oeuvre a bien été créée.' );
                return $this->redirect($this->generateUrl('data_image_fileimage_index'));
            }
        }
        return $this->render('DATAImageBundle:FileImage:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function editAction($view_id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryView = $em->getRepository('DATAImageBundle:View');
        $repositoryImage = $em->getRepository('DATAImageBundle:Image');
        
        $view = $repositoryView->findOneById($view_id);
        if ($view === null) { throw $this->createNotFoundException('View : [id='.$view_id.'] inexistante.'); }
        $image = $repositoryImage->findOneByView($view);
        if ($image === null) { throw $this->createNotFoundException('Image for view : [id='.$view_id.'] inexistante.'); }
        $fileImage = $image->getFileImage();
        if ($fileImage === null) { throw $this->createNotFoundException('Image file for image : [id='.$image->getId().'] inexistante.'); }

        $form = $this->createForm(new FileImageType(), $fileImage);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($fileImage);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Félicitations, l\'item a bien été modifié.' );
                return $this->redirectToRoute('data_data_entity_view', array('id' => $view->getEntity()->getId()));
            }
        }
        return $this->render('DATAImageBundle:FileImage:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
