<?php

namespace DATA\DataBundle\Controller;

use DATA\DataBundle\Entity\SujetAsIconography;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntityWikidataController extends Controller
{
    public function indexAction($id)
    {
        $entity = $this->get('data_data.entity')->find('one', array('id' => $id), 'large');
        if ($entity === null) { throw $this->createNotFoundException('Entité : [id='.$id.'] inexistante.'); }

        return $this->render('DATADataBundle:Entity:Wikidata/view.html.twig', array(
            'entity' => $entity,
            'properties' => $this->get('data_data.entity')->getWikidataProperties($entity)
        ));
    }

    public function removeWikidataPropertyAction($id, $id_property)
    {
        $em = $this->getDoctrine()->getManager();
        $entityProperty = $em->getRepository('DATADataBundle:EntityProperty')->findOneBy(array('id' => $id_property, 'entity' => $id));
        if($entityProperty != null) {
            $em->remove($entityProperty);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'La propriété a bien été supprimée.' );
            return $this->redirectToRoute('data_data_entity_wikidata', array('id' => $id));
        } else {
            $this->get('session')->getFlashBag()->add('notice', 'Cette propriété n\'existe pas' );
            return $this->redirectToRoute('data_data_entity_wikidata', array('id' => $id));
        }

    }
}
