<?php

namespace TB\ModelBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class testedItemResult
{
    protected $em;
    protected $security;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }
    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- GET                 --------------------------------------------- */
    public function getByTestedItemResultSession($testedItemResultSession) {
        return $this->em->getRepository('TBModelBundle:TestedItemResult')->findBy(array('testedItemResultSession' => $testedItemResultSession));
    }
    /* -------------------------------------------------------------------------------------------------------------- */
}
