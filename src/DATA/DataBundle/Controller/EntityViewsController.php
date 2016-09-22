<?php

namespace DATA\DataBundle\Controller;

use DATA\DataBundle\Form\MergeViewsType;
use DATA\DataBundle\Entity\Artwork;
use DATA\DataBundle\Entity\Entity;
use DATA\ImageBundle\Entity\FileImage;
use DATA\ImageBundle\Entity\Image;
use DATA\ImageBundle\Entity\View;
use DATA\ImageBundle\Form\ImageRegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class EntityViewsController extends Controller
{
    public function addViewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->container->get('data_data.entity')->getById($id);
        if ($entity === null) { throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.'); }

        $view = new View();
        $view->setCreateUser($this->getUser());
        $view->setOrderView(count($this->container->get('data_data.entity')->getViews($entity))+1);

        $fileImage = new FileImage();

        $image = new Image();
        $image->setCreateUser($this->getUser());
        $image->setView($view);
        $image->setFileImage($fileImage);

        $view->setEntity($entity);
        
        $form = $this->createForm(new ImageRegisterType(), $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($image);
            $em->persist($fileImage);
            $em->persist($view);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, la vue a bien été créée.' );
            return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $entity->getId())));
        }

        return $this->render('DATAImageBundle:Image:Register/register.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    public function unrelatedViewAction($id)
    {
        $em = $this->getDoctrine()->getManager();        
        $view = $em->getRepository('DATAImageBundle:View')->findOneById($id);

        $artwork = new Artwork();
        $artwork->setSujet('Entité Dupliquée');
        $artwork->setCreateUser($this->getUser());
        
        $entity = new Entity();
        $entity->setArtwork($artwork);
        
        $view->setEntity($entity);
        
        $em->persist($view);
        $em->persist($entity);
        $em->persist($artwork);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'La vue a bien été déliée.' );
        return $this->redirectToRoute('data_data_entity_view', array('id' => $entity->getId()));
    }

    public function exclusionFromClichesAction($view_id)
    {
        $em = $this->getDoctrine()->getManager();
        $viewRepository = $em->getRepository('DATAImageBundle:View');
        $excludeViewRepository = $em->getRepository('CLICHESPlayerBundle:ExcludeView');
        $view = $viewRepository->findOneById($view_id);
        $excludeView = $excludeViewRepository->findOneByView($view);
        $excludeService = $this->container->get('cliches_player.playerexcludeaction');

        if ($view === null) { throw $this->createNotFoundException('View : [id='.$view_id.'] inexistante.'); }

        if($excludeView === null) { 
            $excludeService->excludeView($view);
        } elseif($excludeView !== null) {
            $excludeService->includeView($excludeView);
        }
        
        return $this->redirectToRoute('data_data_entity_view', array('id' => $view->getEntity()->getId()));

    }

    public function orderViewAction($view_id, $currentOrder, $way)
    {
        $em = $this->getDoctrine()->getManager();
        $viewRepository = $em->getRepository('DATAImageBundle:View');
        $view = $viewRepository->findOneById($view_id);

        if ($view === null) { throw $this->createNotFoundException('View : [id='.$view_id.'] inexistante.'); }

        if($way == 'up' AND $currentOrder > 1) {$newOrder = $currentOrder - 1;}
        elseif($way == 'down') {$newOrder = $currentOrder + 1;}
        
        $view->setOrderView($newOrder);
        $em->persist($view);

        $otherView = $viewRepository->findOneBy(array('entity' => $view->getEntity(), 'orderView' => $newOrder));
        $otherView->setOrderView($currentOrder);
        $em->persist($otherView);

        $em->flush();

        return $this->redirectToRoute('data_data_entity_view', array('id' => $view->getEntity()->getId()));        
    }
    
    public function initialOrderViewsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entityService = $this->container->get('data_data.entity');

        $count = 0;
        foreach($entityService->find('all', null, 'large') as $entity) {
            $views = $entityService->getViews($entity);
            
            foreach($views as $key => $view) {
                $view->setOrderView($key+1);
                $em->persist($view);
                ++$count;
            }
        }
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', $count.' vues ont été ordonnées.' );
        return $this->redirectToRoute('data_administration_home_index');
    }

    public function mergeViewsAction($view_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $view = $em->getRepository('DATAImageBundle:View')->findOneById($view_id);
        if ($view === null) { throw $this->createNotFoundException('View : [id='.$view_id.'] inexistante.'); }
        
        $form = $this->createForm(new MergeViewsType(), array(), array(
            'attr' => array('entity' => $view->getEntity(), 'currentView_id' => $view->getId())));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('data_image.view')->mergeViews($form->get('view')->getData(), $view);
                
            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, les vues ont bien été fusionnées.' );
            return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $view->getEntity()->getId())));
        }
        
        return $this->render('DATADataBundle:Entity:View/Views/view-views-merge.html.twig', array(
            'form' => $form->createView(),
            'view' => $view
        ));
    }
}
