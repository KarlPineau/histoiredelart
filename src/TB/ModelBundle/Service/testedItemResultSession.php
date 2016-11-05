<?php

namespace TB\ModelBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class testedItemResultSession
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
    public function getByTestedSession($testedSession) {
        return $this->em->getRepository('TBModelBundle:TestedItemResultSession')->findBy(array('testedSession' => $testedSession));
    }
    /* -------------------------------------------------------------------------------------------------------------- */
}
