<?php

namespace CLICHES\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CLICHES\HomeBundle\Form\HomeLoadType;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction()
    {
        if(isset($_GET['sid']) AND !empty($_GET['sid'])) {
            return $this->render('CLICHESHomeBundle:Home:index.html.twig', array(
                'sid' => $_GET['sid']
            ));
        } else {
            return $this->render('CLICHESHomeBundle:Home:index.html.twig');
        }
        
    }

    public function loadAction(Request $request)
    {
        $form = $this->createForm(new HomeLoadType($this->container->get('cliches_player.playersessionaction')), array(), array(
            'attr' => array('entity_service' => $this->get('data_data.entity'))));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($this->generateUrl('cliches_player_session_load', array(
                'teaching_id' => $form->get('teaching')->getData()->getId(),
                'mode' => $form->get('mode')->getData())));
        }
        return $this->render('CLICHESHomeBundle:Home:load_content.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function versionAction()
    {
        return $this->render('CLICHESHomeBundle:Home:version.html.twig');
    }
    
    public function discoverAction()
    {
        return $this->render('CLICHESHomeBundle:Home:discover.html.twig');
    }

    public function cguAction()
    {
        return $this->render('CLICHESHomeBundle:Home:cgu.html.twig');
    }
}
