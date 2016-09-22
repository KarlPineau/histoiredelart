<?php

namespace DATA\DataBundle\Controller;

use DATA\DataBundle\Entity\EntitySources;
use DATA\DataBundle\Form\EntitySourcesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EntitySourcesController extends Controller
{
    public function sourcesForEntityAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entityService = $this->container->get('data_data.entity');
        $entity = $entityService->getById($id);

        if ($entity === null) { throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.'); }

        $entitySources = new EntitySources();
        $sources = $em->getRepository('DATADataBundle:Source')->findByEntity($entity);
        foreach($sources as $source) {
            $entitySources->addSource($source);
        }
        
        $form = $this->createForm(new EntitySourcesType(), $entitySources);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entitySources);
            foreach($entitySources->getSources() as $source) {
                $source->setEntity($entity);
                $em->persist($source);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, les sources ont bien été modifiées.' );
            return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $entity->getId())));
        }

        return $this->render('DATADataBundle:Entity:Sources/editSources.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }
}
