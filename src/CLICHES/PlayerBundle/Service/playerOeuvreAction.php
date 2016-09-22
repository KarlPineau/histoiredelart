<?php

namespace CLICHES\PlayerBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class playerOeuvreAction 
{
    protected $em;
    protected $security;
    protected $playerProposal;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, playerProposalAction $playerProposal)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->playerProposal = $playerProposal;
    }
    
    public function deletePlayerOeuvre($playerOeuvre)
    {
        $repositoryPlayerProposal = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposal');
        $playersProposal = $repositoryPlayerProposal->findByPlayerOeuvre($playerOeuvre);
        foreach ($playersProposal as $playerProposal) {
            $this->playerProposal->deletePlayerProposal($playerProposal);
        }
        
        $this->em->remove($playerOeuvre);
        $this->em->flush();
    }
    
    public function countByView($view)
    {
        $repositoryPlayerOeuvre = $this->em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $playerOeuvres = $repositoryPlayerOeuvre->findByView($view);
        
        return count($playerOeuvres);
    }
    
    public function getByView($view) 
    {
        $repositoryPlayerOeuvre = $this->em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $playerOeuvres = $repositoryPlayerOeuvre->findByView($view);

        return $playerOeuvres;
    }
}
