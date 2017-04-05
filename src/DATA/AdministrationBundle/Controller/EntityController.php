<?php

namespace DATA\AdministrationBundle\Controller;

use DATA\DataBundle\Entity\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EntityController extends Controller
{
    public function generateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $artworkRepository = $em->getRepository('DATADataBundle:Artwork');
        $buildingRepository = $em->getRepository('DATADataBundle:Building');
        $entityService = $this->get('data_data.entity');

        $count = 0;
        
        foreach($artworkRepository->findAll() as $artwork) {
            if($entityService->find('one', array('artwork' => $artwork), 'large') == null) {            
                $entity = new Entity();
                $entity->setArtwork($artwork);
                $em->persist($entity);
                
                $count++;
            }
        }
        foreach($buildingRepository->findAll() as $building) {
            if($entityService->find('one', array('building' => $building), 'large') == null) {
                $entity = new Entity();
                $entity->setBuilding($building);
                $em->persist($entity);

                $count++;
            }
        }
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Félicitations, '.$count.' entitiés ont été générées.' );
        return $this->redirectToRoute('data_administration_home_index');
    }

    public function normalizationAction()
    {
        set_time_limit(0);
        $em = $this->getDoctrine()->getManager();
        $semanticEnrichments = $em->getRepository('DATADataBundle:SemanticEnrichment')->findAll();

        $finalArray = array();
        $count = 0;

        foreach($semanticEnrichments as $enrichment) {
            if($count <= 400) {
                if($enrichment->getNormalised() != true) {
                    $newValue = null;
                    $qwd = $this->get('data_data.wikidata')->getQwd($enrichment->getUri());

                    $responseWikidata = json_decode($this->get('buzz')->get('http://www.wikidata.org/w/api.php?action=wbgetentities&ids=' . urlencode($qwd) . '&props=labels&format=json')->getContent());
                    $entity = $responseWikidata->entities->{$qwd};
                    if (property_exists($entity->labels, 'fr')) {
                        $newValue = $entity->labels->fr->value;
                    } elseif (property_exists($entity->labels, 'en')) {
                        $newValue = $entity->labels->en->value;
                    }

                    $finalArray[] = ['entity' => $enrichment->getEntity(), 'field' => $enrichment->getField(), 'oldValue' => $this->get('data_data.entity')->get($enrichment->getField(), $enrichment->getEntity()), 'newValue' => $newValue, 'qwd' => $qwd];
                    if ($newValue != null and $newValue != $this->get('data_data.entity')->get($enrichment->getField(), $enrichment->getEntity())) {
                        $this->get('data_data.entity')->set($enrichment->getField(), $enrichment->getEntity(), $newValue);

                    }

                    $enrichment->setNormalised(true);
                    $em->flush();
                    $count++;
                }

            } else {
                break;
            }

        }

        return $this->render('DATAAdministrationBundle:Entity:normalization.html.twig', array(
            'changes' => $finalArray
        ));
    }
}
