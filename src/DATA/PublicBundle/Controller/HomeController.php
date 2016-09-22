<?php

namespace DATA\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('DATATeachingBundle:Teaching');
        $teachings = $repository->findBy(array('onLine' => true));

        $array = ['teachings' => $teachings];
        if(isset($_GET['ppid']) AND !empty($_GET['ppid'])) {$array['ppid'] = $_GET['ppid'];}

        return $this->render('DATAPublicBundle:Home:index.html.twig', $array);
    }
}
