<?php

namespace TOOLS\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function indexAction()
    {
        $arrayDatation = array();
        foreach ($this->get('data_data.entity')->find('all', null, 'large') as $entity) {
            if($this->get('data_data.entity')->get('datation', $entity) != null) {
                $type = $this->container->get('tools_administration.test_datation')->getType($this->get('data_data.entity')->get('datation', $entity));
                $arrayDatation[] = ['type' => $type, 'content' => $this->get('data_data.entity')->get('datation', $entity)];
            }
        }

        //$arrayDatation = array_unique($arrayDatation);
        //asort($arrayDatation);

        return $this->render('TOOLSAdministrationBundle:Test:index.html.twig', array('dates' => $arrayDatation));
    }

    public function creatorAction()
    {
        $middleArray = array();
        foreach ($this->get('data_data.entity')->find('all', null, 'large') as $entity) {
            if($this->get('data_data.entity')->get('auteur', $entity) != null) {
                $middleArray[] = $this->get('data_data.entity')->get('auteur', $entity);
            }
        }

        $array = array();
        foreach($middleArray as $creator) {
            if(isset($array[$creator])) { $array[$creator] = $array[$creator]+1;}
            else{$array[$creator] = 1;}
        }
        natsort($array);

        return $this->render('TOOLSAdministrationBundle:Test:creator.html.twig', array('creators' => $array));
    }
}
