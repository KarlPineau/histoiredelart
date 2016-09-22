<?php

namespace DATA\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PublicController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('CASUserBundle:User');

        $requiredAccess = count($userRepository->findBy(array('dataAccessRequired' => true)));
        $access = count($userRepository->findBy(array('dataAccess' => true)));

        return $this->render('DATAAdministrationBundle:Public:index.html.twig', array(
            'requiredAccess' => $requiredAccess,
            'access' => $access
        ));
    }
}
