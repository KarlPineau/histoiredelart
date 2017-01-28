<?php

namespace DATA\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PublicController extends Controller
{
    public function visitAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $visitsQuery = $em->getRepository('DATAPublicBundle:Visit')->findBy(array(), array('createDate' => 'DESC'));

        $paginator  = $this->get('knp_paginator');
        $visits = $paginator->paginate(
            $visitsQuery,
            $request->query->get('page', 1)/*page number*/,
            300/*limit per page*/
        );

        return $this->render('DATAAdministrationBundle:Public:visit.html.twig', array(
            'visits' => $visits,
        ));
    }
}
