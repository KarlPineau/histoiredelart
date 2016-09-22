<?php

namespace CLICHES\PlayerBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class playerProposalAction 
{
    protected $em;
    protected $security;
    
    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }
    
    public function deletePlayerProposal($playerProposal)
    {
        $this->em->remove($playerProposal);
        $this->em->flush();
    }

    public function getPlayerProposalByPlayerOeuvre($playerOeuvre)
    {
        $repository = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposal');

        return $repository->findOneByPlayerOeuvre($playerOeuvre);
    }
}
