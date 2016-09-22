<?php

namespace DATA\TeachingBundle\Service;

use Doctrine\ORM\EntityManager;

class teachingTest
{
    protected $em;

    public function __construct(EntityManager $EntityManager)
    {
        $this->em = $EntityManager;
    }

    public function getById($id)
    {
        return $this->em->getRepository('DATATeachingBundle:TeachingTest')->findOneById($id);
    }
}
