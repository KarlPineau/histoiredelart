<?php

namespace DATA\DataBundle\Controller;

use DATA\DataBundle\Entity\SujetAsIconography;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntityController extends Controller
{
    public function indexAction(Request $request)
    {
        $entities = $this->get('data_data.entity')->find('all', null, 'large');
        
        $transi = array();
        foreach($entities as $entity) {
            $transi[$entity->getId()] = $this->get('data_data.entity')->getName($entity);
        }
        natsort($transi);

        $return = array();
        foreach($transi as $keyId => $name) {
            $return[] = $keyId;
        }

        $paginator  = $this->get('knp_paginator');
        $listEntities = $paginator->paginate(
            $return,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );

        return $this->render('DATADataBundle:Entity:index.html.twig', array(
                                'listEntities' => $listEntities,
                            ));
    }

    public function getNoSameAsAction(Request $request)
    {
        $entities = $this->get('data_data.entity')->find('all', null, 'large');
        $return = [];

        foreach($entities as $entity) {
            if($this->get('data_data.entity')->getSameAs($entity) == null) {
                array_push($return, $entity);
            }
        }

        $paginator  = $this->get('knp_paginator');
        $listEntities = $paginator->paginate(
            $return,
            $request->query->get('page', 1)/*page number*/,
            300/*limit per page*/
        );

        return $this->render('DATADataBundle:Entity:index.html.twig', array(
            'listEntities' => $listEntities,
        ));
    }

    public function getWithSameAsAction(Request $request)
    {
        $entities = $this->get('data_data.entity')->find('all', null, 'large');
        $return = [];

        foreach($entities as $entity) {
            if($this->get('data_data.entity')->getSameAs($entity) != null) {
                array_push($return, $entity);
            }
        }

        $paginator  = $this->get('knp_paginator');
        $listEntities = $paginator->paginate(
            $return,
            $request->query->get('page', 1)/*page number*/,
            300/*limit per page*/
        );

        return $this->render('DATADataBundle:Entity:index.html.twig', array(
            'listEntities' => $listEntities,
        ));
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPad = $em->getRepository('DATADataBundle:Pad');
        $repositoryVisit = $em->getRepository('DATAPublicBundle:Visit');
        $entityService = $this->container->get('data_data.entity');
        $viewService = $this->container->get('data_image.view');

        $entity = $entityService->find('one', array('id' => $id), 'large');
        if ($entity === null) { throw $this->createNotFoundException('Entité : [id='.$id.'] inexistante.'); }

        $visits = $repositoryVisit->findByEntity($entity);
        $pad = $repositoryPad->findOneByEntity($entity);
        $teachings = $entityService->getTeachings($entity);
        $relatedViews = $viewService->getViewsForEntityAdmin($entity);

        if(count($relatedViews) > 1) {$viewService->checkOrderViews($relatedViews);}

        return $this->render('DATADataBundle:Entity:view.html.twig', array(
            'entity' => $entity,
            'relatedViews' => $relatedViews,
            'teachings' => $teachings,
            'visits' => $visits,
            'pad' => $pad
        ));
    }

    public function switchAction($id)
    {
        $entityService = $this->get('data_data.entity');
        $entity = $entityService->getById($id);

        if ($entity === null) {throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.');}
        
        $entityService->switchTo($entity);

        $this->get('session')->getFlashBag()->add('notice', 'Switch effectué' );
        return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $entity->getId())));
    }

    public function deleteAction($id)
    {
        $entityService = $this->get('data_data.entity');
        $entity = $entityService->getById($id);
        if($entity === null) {throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.');}

        $entityService->removeEntity($entity);

        $this->get('session')->getFlashBag()->add('notice', 'Votre item a bien été supprimé.' );
        return $this->redirectToRoute('data_data_entity_index');
    }

    public function setSujetAsIconographyAction($entity_id)
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $repositorySujetAsIconography = $em->getRepository('DATADataBundle:SujetAsIconography');

            $entityService = $this->get('data_data.entity');
            $entity = $entityService->getById($entity_id);

            if ($entity === null) {throw $this->createNotFoundException('Item : [id='.$entity_id.'] inexistant.');}

            if($repositorySujetAsIconography->findOneByEntity($entity) != null) {
                $em->remove($repositorySujetAsIconography->findOneByEntity($entity));
            } else {
                $sujetAsIconography = new SujetAsIconography();
                $sujetAsIconography->setEntity($entity);
                $em->persist($sujetAsIconography);
            }
            $em->flush();

            return new Response(true);
        }
    }
}
