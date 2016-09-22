<?php

namespace DATA\TeachingBundle\Service;

use DATA\DataBundle\Service\entity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class teaching
{
    protected $em;
    protected $security;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }
    
    public function deleteTeaching($teaching)
    {
        $repositoryPlayerSession = $this->em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $players = $repositoryPlayerSession->findByTeaching($teaching);
        foreach($players as $player) {
            $player->setTeaching();
        }

        foreach($this->em->getRepository('DATAPublicBundle:Visit')->findByTeaching($teaching) as $visit) {$this->em->remove($visit);}

        $this->em->remove($teaching);
        $this->em->flush();
    }
}
