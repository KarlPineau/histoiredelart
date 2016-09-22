<?php

namespace CLICHES\PlayerBundle\Service;

use CLICHES\PlayerBundle\Entity\ExcludeView;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class playerExcludeAction
{
    protected $em;
    protected $security;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }
    
    public function excludeView($view)
    {
        $exclude = new ExcludeView();
        $exclude->setView($view);
        $this->em->persist($exclude);
        $this->em->flush();
    }

    public function includeView($excludeView)
    {
        $this->em->remove($excludeView);
        $this->em->flush();
    }

    public function isExcluded($view)
    {
        if($this->em->getRepository('CLICHESPlayerBundle:ExcludeView')->findOneByView($view) != null) {
            return true;
        } else {
            return false;
        }
    }
}
