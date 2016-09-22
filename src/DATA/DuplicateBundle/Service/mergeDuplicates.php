<?php

namespace DATA\DuplicateBundle\Service;

use DATA\DataBundle\Service\entity;
use DATA\TeachingBundle\Service\teaching;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class mergeDuplicates
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

    public function mergeDuplicates($entityLeft_id, $entityRight_id)
    {
        $right = $this->entity->getById($entityRight_id);
        if ($right === null) { throw $this->createNotFoundException('Erreur : au moins une entité inexistante.'); }

        $left = $this->entity->getById($entityLeft_id);
        if ($left === null) { throw $this->createNotFoundException('Erreur : au moins une entité inexistante.'); }

        $this->entity->removeEntity($right, $left);

        return $left;
    }
}
