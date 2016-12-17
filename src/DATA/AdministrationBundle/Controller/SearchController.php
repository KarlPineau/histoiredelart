<?php

namespace DATA\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function statisticsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $searchesResults = $em->getRepository('DATASearchBundle:SearchLog')->findBy(array(), array('createDate' => 'DESC'));

        $paginator  = $this->get('knp_paginator');
        $searches = $paginator->paginate(
            $searchesResults,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );

        return $this->render('DATAAdministrationBundle:Search:statistics.html.twig', array(
            'searches' => $searches,
        ));

    }
}
