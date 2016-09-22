<?php

namespace DATA\DuplicateBundle\Controller;

use DATA\DuplicateBundle\Entity\Type;
use DATA\DuplicateBundle\Form\TypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TypeController extends Controller
{
    public function indexAction()
    {
        $repositoryType = $this->getDoctrine()->getManager()->getRepository('DATADuplicateBundle:Type');
        $types = $repositoryType->findAll();

        return $this->render('DATADuplicateBundle:Type:index.html.twig', array('types' => $types));
    }

    public function registerAction()
    {
        $type = new Type();

        $form = $this->createForm(new TypeType(), $type);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($type);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Félicitations, le type de mot a bien été créé.' );
                return $this->redirect($this->generateUrl('data_duplicate_type_view', array('slug' => $type->getSlug())));
            }
        }
        return $this->render('DATADuplicateBundle:Type:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function viewAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('DATADuplicateBundle:Type');

        $type = $repository->findOneBySlug($slug);

        if ($type === null) { throw $this->createNotFoundException('Type : [slug='.$slug.'] inexistant.'); }

        return $this->render('DATADuplicateBundle:Type:view.html.twig', array(
            'type' => $type
        ));
    }

    public function editAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('DATADuplicateBundle:Type');

        $type = $repository->findOneBySlug($slug);

        if ($type === null) { throw $this->createNotFoundException('Oeuvre : [slug='.$slug.'] inexistante.'); }

        $form = $this->createForm(new TypeType(), $type);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($type);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Félicitations, votre type a bien été édité.' );
                return $this->redirect($this->generateUrl('data_duplicate_type_view', array('slug' => $type->getSlug())));
            }
        }

        return $this->render('DATADuplicateBundle:Type:edit.html.twig', array(
            'type' => $type,
            'form' => $form->createView(),
        ));
    }

    public function deleteAction($slug)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('DATADuplicateBundle:Type');
        $type = $repository->findOneBySlug($slug);

        if ($type === null) { throw $this->createNotFoundException('Type : [slug='.$slug.'] inexistant.'); }

        $typeAction = $this->container->get('data_duplicate.typeaction');
        $typeAction->deleteType($type);

        $this->get('session')->getFlashBag()->add('notice', 'Votre type a bien été supprimé.' );
        return $this->forward('DATADuplicateBundle:Type:index');
    }
}
