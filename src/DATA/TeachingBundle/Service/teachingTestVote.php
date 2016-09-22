<?php

namespace DATA\TeachingBundle\Service;

use DATA\DataBundle\Service\entity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class teachingTestVote
{
    protected $em;
    protected $security;
    protected $entity;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, entity $entity)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->entityService = $entity;
    }

    public function checkVote($teaching, $view, $user)
    {
        $repositoryTeachingTestVote = $this->em->getRepository('DATATeachingBundle:TeachingTestVote');
        $repositoryTeachingTest = $this->em->getRepository('DATATeachingBundle:TeachingTest');
        $teachingTest = $repositoryTeachingTest->findOneBy(array('teaching' => $teaching, 'view' => $view));
        
        if($teachingTest != null) {
            return $repositoryTeachingTestVote->findOneBy(array('teachingTest' => $teachingTest, 'createUser' => $user));
        } else {
            return null;
        }
    }
}
