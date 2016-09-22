<?php

namespace DATA\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryReporting = $em->getRepository('DATAPublicBundle:Reporting');
        $reportings = count($repositoryReporting->findBy(array('traitement' => false)));

        $import = count($this->get('data_data.entity')->find('all', array('importValidation' => false)));

        return $this->render('DATAAdministrationBundle:Home:index.html.twig', array(
            'reportings' => $reportings,
            'import' => $import
        ));
    }
}
