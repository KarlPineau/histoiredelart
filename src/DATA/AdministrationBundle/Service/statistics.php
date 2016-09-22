<?php

namespace DATA\AdministrationBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use DATA\DataBundle\Service\entity;

class statistics
{
    protected $em;
    protected $security;
    protected $entity;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, entity $entity)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->entity = $entity;
    }

    public function getStats($entityRepository, $field)
    {
        $repository = $this->em->getRepository($entityRepository);
        $resultsGlob = $repository->findAll();
        $resultsEmpty = $repository->findBy(array($field => null));
        $result = (count($resultsEmpty)*100)/count($resultsGlob);

        return $result;
    }
        
}
