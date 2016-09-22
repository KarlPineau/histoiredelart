<?php

namespace DATA\SearchBundle\Controller;

use DATA\SearchBundle\Entity\SearchIndex;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchIndexController extends Controller
{
    public function buildIndexAction()
    {
        set_time_limit(0);
        $em = $this->getDoctrine()->getManager();
        $artworks = $em->getRepository('DATADataBundle:Artwork')->findAll();
        $buildings = $em->getRepository('DATADataBundle:Building')->findAll();
        $entities = array_merge($buildings, $artworks);
        
        $searchIndexRepository = $em->getRepository('DATASearchBundle:SearchIndex');
        $entityService = $this->get('data_data.entity');
        
        foreach($entities as $entity) {
            foreach($entityService->getListFieldsForEntity($entity) as $fieldArray) {
                if($entity->get($fieldArray['field']) != null AND $searchIndexRepository->findOneByValue($entity->get($fieldArray['field'])) == null) {
                    $searchIndex = new SearchIndex();
                    $searchIndex->setValue($entity->get($fieldArray['field']));
                    $em->persist($searchIndex);
                    $em->flush();
                }
            }
        }

        $countIndex = count($searchIndexRepository->findAll());
        $this->get('session')->getFlashBag()->add('notice', 'L\'index de recherche comporte '.$countIndex.' entrées.' );
        return $this->redirectToRoute('data_administration_home_index');
    } 
}
