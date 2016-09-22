<?php

namespace DATA\PublicBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class reporting
{
    protected $em;
    protected $security;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }
    
    public function traitementReporting($reporting)
    {
        $reporting->setTraitement(true);
        $this->em->persist($reporting);
        $this->em->flush();
    }

    public function validateReporting($reporting)
    {
        $reporting->setValidate(true);
        $this->em->persist($reporting);

        $this->traitementReporting($reporting);
    }

    public function refuseReporting($reporting)
    {
        $this->traitementReporting($reporting);
    }
}
