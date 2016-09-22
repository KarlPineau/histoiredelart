<?php

namespace TOOLS\AdministrationBundle\Service;

use DATA\DataBundle\Entity\TimeEntity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class datation
{
    protected $em;
    protected $security;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }
    
    public function getType($datation) {
        if(preg_match('/^\d{3,4}$/', $datation)) {
            return 'date pure';
        } elseif(preg_match('/^[Entre|entre]?\h?(\d{2,4})[\h?\-\h?|\het\h|\hà\h|\h?\/\h?](\d{2,4})$/', $datation)) { //entre /
            return 'interval';
        } elseif(preg_match('/^V.|v.|vers|Vers \d{3,4}$/', $datation)) {
            return 'vers';
        } elseif(preg_match('/siècle|Siècle/', $datation)) {
            return 'siecle';
        } elseif(preg_match('/dynastie|Dynastie/', $datation)) {
            return 'dynastie';
        } else {
            return 'autre';
        }
    }

    public function generateTimeEntity($datation, $entity) {
        $timeEntity = new TimeEntity();
        if($this->security->getToken()->getUser() != null) {$timeEntity->setCreateUser($this->security->getToken()->getUser());}

        if(preg_match('/^\d{3,4}$/', $datation)) {
            $timeEntity->setAbsoluteBeginDateYear($datation);
            $timeEntity->setAbsoluteBeginDate($datation);
        }
    }
}
