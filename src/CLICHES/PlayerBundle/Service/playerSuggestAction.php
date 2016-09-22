<?php

namespace CLICHES\PlayerBundle\Service;

use DATA\DataBundle\Service\entity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class playerSuggestAction 
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

    public function findCurrentValue($playerSuggest)
    {
        $return = '';

        if($playerSuggest->getPlayerSuggestField() == 'vue') {
            $return = $playerSuggest->getView()->getVue();
        } elseif($playerSuggest->getPlayerSuggestField() == 'detail') {
            $return = $playerSuggest->getView()->getDetail();
        } else {
            $fields = $this->entity->getFields($playerSuggest->getView()->getEntity());

            foreach ($fields as $field) {
                if ($field['field'] == $playerSuggest->getPlayerSuggestField()) {
                    $return = $field['value'];
                    break;
                }
            }
        }

        if(empty($return)) {$return = 'Le champ est vide.';}
        return $return;
    }

    public function validateSuggestField($playerSuggest)
    {
        if($playerSuggest->getPlayerSuggestField() != null) {
            if ($playerSuggest->getPlayerSuggestField() == 'vue') {
                $playerSuggest->getView()->setVue($playerSuggest->getPlayerSuggestContent());
                $this->em->persist($playerSuggest->getView());
            } else {
                $entity = $playerSuggest->getView()->getEntity();
                $this->entity->set($playerSuggest->getPlayerSuggestField(), $entity, $playerSuggest->getPlayerSuggestContent());
            }
        }

        $playerSuggest->setPlayerSuggestAccept(true);
        $this->em->persist($playerSuggest);

        $this->em->flush();
        $this->traitementPlayerSuggest($playerSuggest);
    }

    public function refuseSuggestField($playerSuggest)
    {
        $this->traitementPlayerSuggest($playerSuggest);
    }
    
    public function traitementPlayerSuggest($playerSuggest)
    {   
        $playerSuggest->setPlayerSuggestTraitement(true);
        $this->em->persist($playerSuggest);
        $this->em->flush();
    }
    
    public function deletePlayerSuggest($playerSuggest)
    {   
        $this->em->remove($playerSuggest);
        $this->em->flush();
    }
}
