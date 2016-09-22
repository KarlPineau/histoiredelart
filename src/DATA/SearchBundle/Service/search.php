<?php

namespace DATA\SearchBundle\Service;

use DATA\DataBundle\Service\entity;
use DATA\TeachingBundle\Service\teaching;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class search 
{
    protected $em;
    protected $security;
    protected $teaching;
    protected $entity;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, teaching $teaching, entity $entity)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->teaching = $teaching;
        $this->entity = $entity;
    }
    
    public function search($searchText, $teaching_slug)
    {
        $words = explode(' ', $searchText);
        $listEntities= array();
        $countEntities = array();

        foreach($words as $keyWord => $word) {
            $listEntities = array_merge($listEntities, $this->searchArtworks($word));
            $listEntities = array_merge($listEntities, $this->searchBuildings($word));
        }

        foreach($listEntities as $key => $entity) {
            $listEntities[$key] = $this->entity->getPure($entity);
        }

        foreach($listEntities as $key => $entity) {
            if($entity == null) {
                unset($listEntities[$key]);
            }
            elseif(array_key_exists($entity->getId(), $countEntities)) {
                $nb = $countEntities[$entity->getId()];
                $countEntities[$entity->getId()] = $nb + 1;
            } else {
                $countEntities[$entity->getId()] = 1;
            }
        }

        foreach ($countEntities as $idEntity => $count) {
            if($count != count($words)) {
                unset($countEntities[$idEntity]);
            }
        }
        natsort($countEntities);

        if($teaching_slug != "null" and $teaching_slug != null) {
            $teaching = $this->em->getRepository('DATATeachingBundle:Teaching')->findOneBySlug($teaching_slug);
            if($teaching !== null) {
                foreach ($countEntities as $idEntity => $count) {
                    $entity = $this->entity->find('one', array('id' => $idEntity));
                    $concerned = false;
                    $teachingsForEntity = $this->entity->getTeachings($entity);
                    foreach ($teachingsForEntity as $teachingForEntity) {
                        if ($teachingForEntity == $entity) {
                            $concerned = true;
                        }
                    }

                    if ($concerned == false) {
                        unset($countEntities[$idEntity]);
                    }
                }
            }
        }
    
        //return $countEntities;

        $returnArray = array();
        foreach($countEntities as $idEntity => $count) {
            $returnArray[] = $this->entity->find('one', array('id' => $idEntity));
        }
        return $returnArray;
    }
    
    public function searchArtworks($searchText)
    {
        return $this->em->getRepository('DATADataBundle:Artwork')
                        ->createQueryBuilder('a')
                        ->where('a.sujet LIKE :searchText')
                        ->orWhere('a.sujetIcono LIKE :searchText')
                        ->orWhere('a.auteur LIKE :searchText')
                        ->orWhere('a.datation LIKE :searchText')
                        ->orWhere('a.lieuDeConservation LIKE :searchText')
                        ->setParameter('searchText', '%'.$searchText.'%')
                        ->orderBy('a.sujet')
                        ->getQuery()
                        ->getResult();
    }
    
    public function searchBuildings($searchText)
    {
        return $this->em->getRepository('DATADataBundle:Building')
                        ->createQueryBuilder('b')
                        ->where('b.name LIKE :searchText')
                        ->orWhere('b.auteur LIKE :searchText')
                        ->orWhere('b.datation LIKE :searchText')
                        ->setParameter('searchText', '%'.$searchText.'%')
                        ->orderBy('b.name')
                        ->getQuery()
                        ->getResult();
    }
    
}
