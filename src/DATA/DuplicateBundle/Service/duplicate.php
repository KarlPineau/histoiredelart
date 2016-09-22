<?php

namespace DATA\DuplicateBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class duplicate
{
    protected $em;
    protected $security;
    protected $analysis;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, analysis $analysis)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->analysis = $analysis;
    }

    public function findArtworkDuplicate($entity=null, $attribute=null)
    {
        if($entity == null) {
            return $this->findArtworkDuplicateGlobal();
        } else {
            return $this->findArtworkDuplicateForEntity($entity, $attribute);
        }
    }

    public function findArtworkDuplicateGlobal() {
        $artworkRepository = $this->em->getRepository('DATADataBundle:Artwork');
        $artworks = $artworkRepository->findAll();

        $listArtworksDetected = array();
        foreach ($artworks as $key => $artwork) {
            $artworksDetected = $artworkRepository->findBySujet($artwork->getSujet());

            if (count($artworksDetected) > 1) {
                foreach ($artworksDetected as $artworkDetected) {
                    if ($artworkDetected != $artwork) {
                        $listArtworksDetected[$key] =
                            ['entity' => $artwork,
                            'detected' => $artworkDetected,
                            'type' => "artwork"];
                    }
                }
            }
        }

        return $listArtworksDetected;
    }

    public function findArtworkDuplicateForEntity($entity, $attribute) {
        $artworkRepository = $this->em->getRepository('DATADataBundle:Artwork');
        $artworksDetected = $artworkRepository->findBySujet($entity->get($attribute));

        $artworksDetectedToAdd = array();
        if (count($artworksDetected) > 0) {
            foreach ($artworksDetected as $artworkDetected) {
                if ($artworkDetected != $entity) {
                    $artworksDetectedToAdd[] =
                            ['entity' => $entity,
                            'detected' => $artworkDetected,
                            'type' => "artwork"];
                }
            }
        }

        return $artworksDetectedToAdd;
    }

    public function findBuildingDuplicate($entity=null, $attribute=null)
    {
        if($entity == null) {
            return $this->findBuildingDuplicateGlobal();
        } else {
            return $this->findBuildingDuplicateForEntity($entity, $attribute);
        }
    }

    public function findBuildingDuplicateGlobal()
    {
        $list1 = $this->findBuildingDuplicateGlobalByName();
        $list2 = $this->analysis->globalAnalysis();

        return array_merge($list1, $list2);
    }

    public function findBuildingDuplicateForEntity($entity, $attribute)
    {
        $buildingRepository = $this->em->getRepository('DATADataBundle:Building');
        $buildings = $buildingRepository->findByName($entity->get($attribute));

        $listBuildingsDetected = array();
        if(count($buildings) > 1) {
            foreach($buildings as $buildingDetected) {
                if($buildingDetected != $entity) {
                    $listBuildingsDetected[] =
                        ['entity' => $entity,
                        'detected' => $buildingDetected,
                        'type' => "building"];
                }
            }
        }
        return $listBuildingsDetected;
    }

    public function findBuildingDuplicateGlobalByName()
    {
        $buildingRepository = $this->em->getRepository('DATADataBundle:Building');
        $buildings = $buildingRepository->findAll();

        $listBuildingsDetected = array();
        foreach($buildings as $key => $building) {
            /* -- Recherche des doublons par correspondance pure des noms -- */
            $buildingsDetected = $buildingRepository->findByName($building->getName());

            if(count($buildingsDetected) > 1) {
                foreach($buildingsDetected as $buildingDetected) {
                    if($buildingDetected != $building) {
                        $listBuildingsDetected[$key] =
                            ['entity' => $building,
                                'detected' => $buildingDetected,
                                'type' => "building"];
                    }


                }
            }
        }
        return $listBuildingsDetected;
    }
}
