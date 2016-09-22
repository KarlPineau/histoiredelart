<?php

namespace CLICHES\PlayerBundle\Service;

use DATA\DataBundle\Service\entity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class playerSelectionAction
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

    //Fonction retournant une oeuvre au hasard valable pour une session
    public function getRandViewForTeaching($teaching, $passedPlayerOeuvres)
    {
        $arrayListEntities = $teaching->getEntities()->getValues();
        $entity = null;

        //$arrayTest = array();
        if (count($arrayListEntities) > 0 AND count($passedPlayerOeuvres) > 0) {
            foreach ($arrayListEntities as $keyListEntity => $arrayListEntity) {
                foreach ($passedPlayerOeuvres as $passedPlayerOeuvre) {
                    if ($this->entity->getByView($passedPlayerOeuvre->getView()) == $arrayListEntity) {
                        unset($arrayListEntities[$keyListEntity]);
                    }
                }
            }
        }
        //Réindexation du tableau pour supprimer les entrées vides
        $arrayListEntities = array_values(array_filter($arrayListEntities));

        /*$arrayTest = array();
        foreach ($passedPlayerOeuvres as $passedPlayerOeuvre) {
            $arrayTest[] = $this->entity->getByView($passedPlayerOeuvre->getView());
        }
        return $arrayTest;*/

        if((count($arrayListEntities) == 1)) {
            $entity = $arrayListEntities[0];
        }
        elseif(count($arrayListEntities) > 1) {
            $numberSelected = rand(0, (count($arrayListEntities) - 1));
            $entity = $arrayListEntities[$numberSelected];
        }
        elseif(count($arrayListEntities) == 0) {
            $entity = null;
        }

        if($entity == null) {
            return ['no_enough_entities', null];
        } else {
            $views= $this->entity->getViews($entity);

            if(count($views) > 0) {
                return ['view', $views[rand(0, (count($views) - 1))]];
            }
            else {
                return ['no_entities', null];
            }
        }
    }
}
