<?php

namespace DATA\PublicBundle\Controller;

use DATA\PublicBundle\Entity\Visit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TeachingController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('DATATeachingBundle:Teaching');
        $teachings = $repository->findBy(array('onLine' => true));

        return $this->render('DATAPublicBundle:Teaching:index.html.twig', array(
            'teachings' => $teachings
        ));
    }

    public function viewAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneBySlug($slug);
        if ($teaching === null) { throw $this->createNotFoundException('Enseignement : [slug='.$slug.'] inexistant.'); }
        if ($teaching->getOnLine() == false) { throw $this->createNotFoundException('Cet enseignement n\'est pas disponible.'); }

        $visit = new Visit();
        $visit->setTeaching($teaching);
        $visit->setCreateUser($this->getUser());
        $em->persist($visit);
        $em->flush();

        $entities = $this->get('data_data.entity')->getByTeaching($teaching, 'restrict');
        $paginator  = $this->get('knp_paginator');
        $listEntities = $paginator->paginate(
            $entities,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );
        
        return $this->render('DATAPublicBundle:Teaching:view.html.twig', array(
            'teaching' => $teaching,
            'listEntities' => $listEntities
        ));
    }
}
