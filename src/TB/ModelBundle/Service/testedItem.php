<?php

namespace TB\ModelBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class testedItem
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
    public function getByTestedGame($testedGame) {
        return $this->em->getRepository('TBModelBundle:TestedItem')->findBy(array('testedGame' => $testedGame));
    }
    /* -------------------------------------------------------------------------------------------------------------- */
}
