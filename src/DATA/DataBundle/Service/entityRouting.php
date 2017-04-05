<?php

namespace DATA\DataBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class entityRouting
{
    protected $em;
    protected $security;
    protected $artwork;
    protected $building;
    protected $artworkQuarantine;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }

    public function getRoutingView() {return 'data_data_entity_view';}

    public function getRoutingPublicView() {return 'data_public_entity_view';}

    public function getRoutingEditViews($entity)
    {
        if($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Artwork') {
            return 'data_data_artwork_edit_views';
        } elseif($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Building') {
            return 'data_data_building_edit_views';
        }

        elseif($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Entity') {
            if($entity->getArtwork() != null) {return $this->getRoutingEditViews($entity->getArtwork());}
            elseif($entity->getBuilding() != null) {return $this->getRoutingEditViews($entity->getBuilding());}
        }
    }

    public function getRoutingEditTeaching() {return 'data_data_entity_edit_teaching';}

    public function getRoutingDeleteTeaching() {return 'data_data_entity_delete_teaching';}

    public function getRoutingEdit($entity)
    {
        if($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Artwork') {
            return 'data_data_artwork_edit';
        } elseif($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Building') {
            return 'data_data_building_edit';
        }

        elseif($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Entity') {
            if($entity->getArtwork() != null) {return $this->getRoutingEdit($entity->getArtwork());}
            elseif($entity->getBuilding() != null) {return $this->getRoutingEdit($entity->getBuilding());}
        }
    }

    public function getRoutingSwitch() {return 'data_data_entity_switch';}

    public function getRoutingDelete() {return 'data_data_entity_delete';}

    public function getRoutingViewWikidata() {return 'data_data_entity_wikidata';}
}
