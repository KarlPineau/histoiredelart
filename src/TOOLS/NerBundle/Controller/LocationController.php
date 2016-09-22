<?php

namespace TOOLS\NerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LocationController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nameEntityRecognition = $em->getRepository('TOOLSNerBundle:NameEntityRecognition')->findBy(array('field' => 'lieuDeConservation', 'isVerified' => false));

        return $this->render('TOOLSNerBundle:Index:index.html.twig', array(
            'entities' => $nameEntityRecognition,
        ));
    }

    public function generateLocationAction($id)
    {
        $entity = $this->container->get('data_data.entity')->find('one', array('id' => $id), 'large');
        if ($entity === null) { throw $this->createNotFoundException('Entité : [id='.$id.'] inexistante.'); }

        $this->get('tools_ner.ner')->nameEntityRecognitionCreator($entity->getId(), 'lieuDeConservation');

        $entities = array();
        $entity = $this->getDoctrine()->getManager()->getRepository('TOOLSNerBundle:NameEntityRecognition')->findOneByUsedIn($entity);
        array_push($entities, $entity);

        return $this->render('TOOLSNerBundle:Index:index.html.twig', array(
            'entities' => $entities,
        ));

    }
}
