<?php

namespace TB\PersonalPlaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PublicController extends Controller
{
    public function indexAction($user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('CASUserBundle:User')->findOneById($user_id);
        if($user === null) {throw $this->createNotFoundException('Error: no user');}
        $testedGames = $em->getRepository('TBModelBundle:TestedGame')->findBy(array('createUser' => $user, 'isPrivate' => false, 'isOnline' => true));

        return $this->render('TBPersonalPlaceBundle:Public:index.html.twig', array(
            'testedGames' => $testedGames,
            'user' => $user
        ));
    }
}
