<?php

namespace DATA\PersonalPlaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FavoriteController extends Controller
{
    public function indexAction(Request $request)
    {
        $repositoryFavorite = $this->getDoctrine()->getManager()->getRepository('CASUserBundle:Favorite');
        $favoritesList = $repositoryFavorite->findByUser($this->getUser());

        $paginator  = $this->get('knp_paginator');
        $favorites = $paginator->paginate(
            $favoritesList,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );

        return $this->render('DATAPersonalPlaceBundle:Favorite:index.html.twig', array(
            'favorites' => $favorites
        ));
    }
}
