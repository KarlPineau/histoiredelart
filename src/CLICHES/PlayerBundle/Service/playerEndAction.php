<?php

namespace CLICHES\PlayerBundle\Service;

use CLICHES\PlayerBundle\Entity\ExcludeView;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class playerEndAction
{
    protected $em;
    protected $security;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }

    public function getBySession($session) {
        return $this->em->getRepository('CLICHESPlayerBundle:PlayerEndViews')->findOneByPlayerSession($session);
    }
}
