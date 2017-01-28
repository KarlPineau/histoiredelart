<?php

namespace DATA\PublicBundle\Controller;

use CLICHES\PersonalPlaceBundle\Entity\PrivatePlayerView;
use DATA\PersonalPlaceBundle\Entity\UserSessions;
use DATA\PersonalPlaceBundle\Form\UserSessionsType;
use DATA\PublicBundle\Entity\Reporting;
use DATA\PublicBundle\Entity\Visit;
use DATA\PublicBundle\Form\ReportingType;
use CAS\UserBundle\Entity\Favorite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntityController extends Controller
{
    public function indexAction(Request $request)
    {
        $entities = $this->get('data_data.entity')->find('all', null, 'restrict');

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

        return $this->render('DATAPublicBundle:Entity:index.html.twig', array(
            'listEntities' => $listEntities
        ));
    }

    public function viewJsonAction($id) {
        $entity = $this->get('data_data.entity')->find('one', array('id' => $id));
        if ($entity === null) { throw $this->createNotFoundException('Entité : [id='.$id.'] inexistante.'); }

        if($entity->getArtwork() != null) {
            $type = 'artwork';
            $metadata = $entity->getArtwork();
        } elseif($entity->getBuilding() != null) {
            $type = 'artwork';
            $metadata = $entity->getBuilding();
        }

        $arrayReturn = array(
            'entity' => $entity->getId(),
            'type' => $type,
            'metadata' => []
        );

        foreach($this->get('data_data.entity')->getListFieldsForEntity($entity) as $field)
        {
            if($field['field'] == 'sujetIcono' and $this->get('data_data.entity')->isSujetAsIconography($entity) == true) {$arrayReturn['metadata'][$field['field']] = 'Iconographie similaire au sujet';}
            elseif($this->get('data_data.entity')->valueByField($field['field'], $entity) == 'unrelevant') {$arrayReturn['metadata'][$field['field']] = 'Champ non pertinent';}
            elseif($this->get('data_data.entity')->valueByField($field['field'], $entity) == 'empty') {$arrayReturn['metadata'][$field['field']] = 'Champ vide';}
            else {$arrayReturn['metadata'][$field['field']] = $this->get('data_data.entity')->valueByField($field['field'], $entity);}
        }


        return $this->render('DATAPublicBundle:Entity:viewJson.html.twig', array('entity' => $arrayReturn));
    }

    public function viewAction($id, $context, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entityService = $this->container->get('data_data.entity');
        $entity = $entityService->getById($id);
        
        if ($entity === null) { throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.'); }

        $relatedViews = $entityService->getViews($entity);
        $teachings = $entityService->getTeachings($entity);
        
        if($this->getUser() != null) {
            $isFavorite = $em->getRepository('CASUserBundle:Favorite')->findOneBy(array('user' => $this->getUser(), 'entity' => $entity));
        } else {
            $isFavorite = false;
        }

        $arrayReturn = array();
        if(isset($_GET['reportingBoolean']) AND $_GET['reportingBoolean'] == 'true') {
            $reporting = new Reporting();
            $reporting->setCreateUser($this->getUser());
            $reporting->setTraitement(false);
            $reporting->setEntity($entity);

            $form = $this->createForm(new ReportingType(), $reporting);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($reporting);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Votre signalement a bien été transmis. Merci pour votre contribution.' );
                return $this->redirectToRoute('data_public_entity_view', array('id' => $entity->getId()));
            }

            $arrayReturn['form'] = $form->createView();

        } else {
            $visit = new Visit();
            $visit->setEntity($entity);
            if($context == "null") {$context = null;} $visit->setContext($context);
            $visit->setCreateUser($this->getUser());
            $em->persist($visit);
            $em->flush();
        }

        $arrayReturn['entity'] = $entity;
        $arrayReturn['relatedViews'] = $relatedViews;
        $arrayReturn['teachings'] = $teachings;
        $arrayReturn['isFavorite'] = $isFavorite;
        if(isset($_GET['search']) AND !empty($_GET['search'])) {$arrayReturn['search_id'] = $_GET['search'];}
        if(isset($_GET['ppid']) AND !empty($_GET['ppid'])) {$arrayReturn['ppid'] = $_GET['ppid'];}
        if($this->getUser() != null) {
            $arrayReturn['privateSessions'] = $em->getRepository('CLICHESPersonalPlaceBundle:PrivatePlayer')->findBy(array('createUser' => $this->getUser()), array('createDate' => 'DESC'));
        }

        return $this->render('DATAPublicBundle:Entity:view.html.twig', $arrayReturn);
    }

    public function favoriteAction($id)
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()) {
            $entity = $this->get('data_data.entity')->getById($id);
            if($entity == null) {return new Response(json_encode(false));}

            $em = $this->getDoctrine()->getManager();
            $repositoryFavorite = $em->getRepository('CASUserBundle:Favorite');
            $favorites = $repositoryFavorite->findBy(array('user' => $this->getUser(), 'entity' => $entity));

            if(count($favorites) > 0) {
                foreach ($favorites as $favoriteItem) {
                    $em->remove($favoriteItem);
                }
            } else {
                $favorite = new Favorite();
                $favorite->setEntity($entity);
                $favorite->setUser($this->getUser());
                $em->persist($favorite);
            }
            $em->flush();

            return new Response(json_encode(true));
        }
    }

    public function addViewToPrivateSessionAction($view_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $privateSessions = $em->getRepository('CLICHESPersonalPlaceBundle:PrivatePlayer')->findBy(array('createUser' => $this->getUser()), array('createDate' => 'DESC'));

        $view = $em->getRepository('DATAImageBundle:View')->findOneById($view_id);
        if ($view === null) { throw $this->createNotFoundException('View : [id='.$view_id.'] inexistant.'); }

        $userSessions = new UserSessions();
        $userSessions->setCreateUser($this->getUser());
        $userSessions->setView($view);
        foreach($privateSessions as $privateSession) {
            $userSessions->addPrivatePlayer($privateSession);
        }

        $form = $this->createForm(new UserSessionsType(), $userSessions, array('attr' => array('user' => $this->getUser())));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $privatePlayerView = new PrivatePlayerView();
            $privatePlayerView->setCreateUser($this->getUser());
            $privatePlayerView->setIpCreateUser($this->container->get('request')->getClientIp());
            $privatePlayerView->setPrivatePlayer($form->get('privatePlayers')->getData());
            $privatePlayerView->setView($view);
            $em->persist($privatePlayerView);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, l\'oeuvre a bien été ajoutée à votre partie.');
            return $this->redirectToRoute('data_public_entity_view', array('id' => $this->get('data_data.entity')->getByView($view)->getId()));
        }

        return $this->render('DATAPublicBundle:Entity:View/view-privatePlayer.html.twig', array(
            'view' => $view,
            'form' => $form->createView())
        );
    }
}
