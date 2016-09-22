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
}
