<?php

namespace DATA\DataBundle\Controller;

use DATA\DataBundle\Entity\Pad;
use DATA\DataBundle\Form\PadType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class PadController extends Controller
{
    public function editPadAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->container->get('data_data.entity')->getById($id);
        if ($entity === null) { throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.'); }

        //On vérifie qu'il n'existe pas déjà un pad pour cette entité :
        $pad = $em->getRepository("DATADataBundle:Pad")->findOneByEntity($entity);
        if ($pad == null)
        {
            $pad = new Pad();
            if($this->getUser() != null) {$pad->setCreateUser($this->getUser());}
            $pad->setEntity($entity);
        }

        $form = $this->createForm(new PadType(), $pad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pad);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Pad édité.' );
            return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $entity->getId())));
        }

        return $this->render('DATADataBundle:Entity:Pad/edit.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));
    }
}
