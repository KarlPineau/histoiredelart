<?php

namespace CLICHES\AdministrationBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class modifications
{
    protected $em;
    protected $security;
    
    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }
    
    public function countAll()
    {
        $repositoryPlayerSuggest = $this->em->getRepository('CLICHESPlayerBundle:PlayerSuggest');
        $playerSuggests = $repositoryPlayerSuggest->findBy(array('playerSuggestTraitement' => false), array('createDate' => 'ASC'));

        $repositoryUnrelevant = $this->em->getRepository('DATADataBundle:UnrelevantField');
        $unrelevantField = $repositoryUnrelevant->findByConfirmed(false);
        
        return count($playerSuggests)+count($unrelevantField);
    }
}
