<?php

namespace TB\ModelBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class testedGame
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
    /* -------------------------------------------- Actions             --------------------------------------------- */
    public function remove($testedGame) {
        foreach($this->em->getRepository('TBModelBundle:TestedItem')->findByTestedGame($testedGame) as $testedItem) {
            foreach($this->em->getRepository('TBModelBundle:TestedItemResult')->findByTestedItem($testedItem) as $testedItemResult) {
                $this->em->remove($testedItemResult);
            }

            $this->em->remove($testedItem);
        }

        foreach($this->em->getRepository('TBModelBundle:TestedSession')->findByTestedGame($testedGame) as $testedSession) {
            $this->em->remove($testedSession);
        }

        $this->em->remove($testedGame);
        $this->em->flush();
    }
    /* -------------------------------------------------------------------------------------------------------------- */
}
