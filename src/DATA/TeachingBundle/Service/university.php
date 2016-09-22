<?php

namespace DATA\TeachingBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class university
{
    protected $em;
    protected $security;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }
    
    public function deleteUniversity($university)
    {
        $repositoryTeaching = $this->em->getRepository('DATATeachingBundle:Teaching');
        $teachings = $repositoryTeaching->findByUniversity($university);
        foreach($teachings as $teaching) {
            $teaching->setUniversity(null);
        }

        $this->em->remove($university);
        $this->em->flush();
    }

    public function getTeachings($university, $scope=null)
    {
        $repositoryTeaching = $this->em->getRepository('DATATeachingBundle:Teaching');

        if($scope=='restrict') {
            return $repositoryTeaching->findBy(array('university' => $university, 'onLine' => true));
        } else {
            return $repositoryTeaching->findByUniversity($university);
        }
    }
}
