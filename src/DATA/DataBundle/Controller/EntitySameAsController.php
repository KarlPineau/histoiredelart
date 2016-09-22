<?php

namespace DATA\DataBundle\Controller;

use DATA\DataBundle\Entity\EntitySameAs;
use DATA\DataBundle\Form\EntitySameAsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EntitySameAsController extends Controller
{
    public function getAllSameAsForEntityAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entityService = $this->container->get('data_data.entity');
        $entity = $entityService->getById($id);
        if ($entity === null) { throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.'); }

        $entitySameAs = new EntitySameAs();
        $allSamesAs = $em->getRepository('DATADataBundle:SameAs')->findByEntity($entity);
        foreach($allSamesAs as $samesAs) {
            $entitySameAs->addSameA($samesAs);
        }
        
        $form = $this->createForm(new EntitySameAsType(), $entitySameAs);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entitySameAs);
            foreach($entitySameAs->getSameAs() as $sameAs) {
                $sameAs->setEntity($entity);
                $em->persist($sameAs);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, les SameAs ont bien été modifiés.' );
            return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $entity->getId())));
        }

        return $this->render('DATADataBundle:Entity:SameAs/editSameAs.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }
}
