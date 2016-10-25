<?php

namespace CLICHES\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller
{
    public function indexAction()
    {
        set_time_limit(0);
        $em = $this->getDoctrine()->getManager();

        $nullValue = $em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->countNull();
        $totalValue = $em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->countAll();

        //$nullValue = $em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->findBy(array('value' => null))->count();
        //$totalValue = $em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->findAll()->count();

        return $this->render('CLICHESAdministrationBundle:Test:index.html.twig', array(
                'dump' => [$nullValue, $totalValue]
        ));
    }
}
