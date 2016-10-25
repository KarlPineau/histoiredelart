<?php

namespace CLICHES\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $modificationsService = $this->container->get('cliches_administration.modifications');
        $countModifications = $modificationsService->countAll();

        $sessions = $em->getRepository('CLICHESPlayerBundle:PlayerSession')->findBy(array(), array('createDate' => 'DESC'), 20);
        
        return $this->render('CLICHESAdministrationBundle:Homepage:index.html.twig', array(
                'countModifications' => $countModifications,
                'sessions' => $sessions,
        ));
    }
}
