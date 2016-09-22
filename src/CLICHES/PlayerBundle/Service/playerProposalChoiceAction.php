<?php

namespace CLICHES\PlayerBundle\Service;

use Doctrine\ORM\EntityManager;

class playerProposalChoiceAction
{
    protected $em;
    protected $entity;


    public function __construct(EntityManager $EntityManager, \DATA\DataBundle\Service\entity $entity)
    {
        $this->em = $EntityManager;
        $this->entity = $entity;
    }


    private function getValueForEntity($values, $field, $entitiesSetByTeaching, $entitiesSizeIndex, $listValues)
    {
        $indexRandom = rand(0, $entitiesSizeIndex);
        if($this->entity->isPropertyExist($entitiesSetByTeaching[$indexRandom], $field) == false) {
            return $this->getValueForEntity($values, $field, $entitiesSetByTeaching, $entitiesSizeIndex, $listValues);
        } else {
            $valueRandom = $this->entity->get($field, $entitiesSetByTeaching[$indexRandom]);
            if ($valueRandom != null AND !in_array($valueRandom, $listValues)) {
                $listValues[] = $valueRandom;
                return ['values' => ['quizz' => false, 'value' => $valueRandom, 'entity' => $entitiesSetByTeaching[$indexRandom]], 'listValues' => $listValues];
            } else {
                return $this->getValueForEntity($values, $field, $entitiesSetByTeaching, $entitiesSizeIndex, $listValues);
            }
        }
    }

    public function getValuesAction($playerOeuvre, $field, $choices_number)
    {
        $view = $playerOeuvre->getView();
        $teaching = $playerOeuvre->getPlayerSession()->getTeaching();
        $entity = $this->entity->getByView($view);

        $entitiesSetByTeaching = $this->entity->getByTeaching($teaching, 'restrict');
        $entitiesSizeIndex = count($entitiesSetByTeaching)-1;

        $listValues[] = $this->entity->get($field, $entity);
        $values = array();
        $values[] = ['quizz' => true, 'value' => $listValues[0], 'entity' => $entity];
        for($i = 0 ; $i < ($choices_number-1) ; $i++) {
            $returnFunctionValue = $this->getValueForEntity($values, $field, $entitiesSetByTeaching, $entitiesSizeIndex, $listValues);
            $values[] = $returnFunctionValue['values'];
            $listValues = $returnFunctionValue['listValues'];
        }

        return $values;
    }
}
