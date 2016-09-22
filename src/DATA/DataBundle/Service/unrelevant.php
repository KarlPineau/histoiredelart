<?php

namespace DATA\DataBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use DATA\DataBundle\Entity\UnrelevantField;

class unrelevant
{
    protected $em;
    protected $security;
    protected $entity;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, entity $entity)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->entity = $entity;
    }

    public function setUnrelevant($field, $entity, $view=null, $confirm=false, $user=null, $userIp=null)
    {
        if($view != null) {$entity = $this->entity->getByView($view);}

        $unrelevantField = new UnrelevantField;
        $unrelevantField->setField($field);
        if($confirm == false) {$unrelevantField->setConfirmed(false);} else {$unrelevantField->setConfirmed(true);}
        if($user != null) {$unrelevantField->setCreateUser($user);}
        if($userIp != null) {$unrelevantField->setIpCreateUser($userIp);}
        $unrelevantField->setEntity($entity);
        
        $this->em->persist($unrelevantField);
        $this->em->flush();
    }

    public function traitementUnrelevantField($unrelevantField)
    {
        $unrelevantField->setConfirmed(true);
        $this->em->persist($unrelevantField);
        $this->em->flush();
    }

    public function validateUnrelevantField($unrelevantField)
    {
        $this->traitementUnrelevantField($unrelevantField);
    }

    public function unValidateUnrelevantField($unrelevantField)
    {
        $this->deleteUnrelevantField($unrelevantField);
    }

    public function refuseUnrelevantField($unrelevantField)
    {
        $this->deleteUnrelevantField($unrelevantField);
    }

    public function deleteUnrelevantField($unrelevantField)
    {
        $this->em->remove($unrelevantField);
        $this->em->flush();
    }

    public function getByField($entity, $field)
    {
        return $this->em->getRepository('DATADataBundle:UnrelevantField')->findOneBy(array('entity' => $entity, 'field' => $field));
    }
    
}
