<?php

namespace DATA\ImageBundle\Controller;

use DATA\ImageBundle\Form\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImageController extends Controller
{
    public function renderImageAction($view_id, $class, $id=0, $clichesnumber=0, $session=0, $image_id=0)
    {
        $em = $this->getDoctrine()->getManager();

        if($view_id === null AND $image_id == 0) {
            throw $this->createNotFoundException('Erreur de chargement de l\'image : identifiant inexistant');
        }

        if($view_id !== null AND $view_id != 0) {
            $view = $em->getRepository('DATAImageBundle:View')->findOneById($view_id);
            if ($view === null) {
            }
            $image = $em->getRepository('DATAImageBundle:Image')->findOneByView($view);

        } elseif($image_id != 0) {
            $image = $em->getRepository('DATAImageBundle:Image')->findOneById($image_id);
        } else {
            throw $this->createNotFoundException('Erreur de chargement de l\'image : identifiant inexistant');
        }

        if ($image === null) {throw $this->createNotFoundException('Image for view : [id=' . $view_id . '] inexistante.');}

        if($id == 0) { $id = null;}
        if($clichesnumber == 0) { $clichesnumber = null; }
        if($session == 0) { $session = null; }

        $isFancybox = false;
        $arrayClass = explode(' ', $class);
        foreach ($arrayClass as $keyArrayClass => $classAlone) {
            if($classAlone == 'fancybox') {
                $isFancybox = true;
                unset($arrayClass[$keyArrayClass]);
            }
        }
        if($isFancybox == true) {
            $class = implode(' ', $arrayClass);
        }

        return $this->render('DATAImageBundle:Image:renderImage.html.twig', array(
                'image' => $image,
                'class' => $class,
                'isFancybox' => $isFancybox,
                'id' => $id,
                'clichesnumber' => $clichesnumber,
                'session' => $session
        ));
    }

    public function editAction($view_id)
    {
        $em = $this->getDoctrine()->getManager();
        $view = $em->getRepository('DATAImageBundle:View')->findOneById($view_id);
        if ($view === null) { throw $this->createNotFoundException('View : [id='.$view_id.'] inexistante.'); }
        $image = $em->getRepository('DATAImageBundle:Image')->findOneByView($view);
        if ($image === null) { throw $this->createNotFoundException('Image for view : [id='.$view_id.'] inexistante.'); }
        $entity = $this->get('data_data.entity')->getByView($view);
        if ($entity === null) { throw $this->createNotFoundException('Item by view : [id='.$view_id.'] inexistant.'); }
        
        $form = $this->createForm(new ImageType(), $image);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($image);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Félicitations, l\'item a bien été modifié.' );
                return $this->redirectToRoute('data_data_entity_view', array('id' => $view->getEntity()->getId()));
            }
        }
        return $this->render('DATAImageBundle:Image:Edit/edit.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));
    }
}
