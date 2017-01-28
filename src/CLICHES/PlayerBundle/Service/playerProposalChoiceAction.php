<?php

namespace CLICHES\PlayerBundle\Service;

use Doctrine\ORM\EntityManager;

class playerProposalChoiceAction
{
    protected $em;
    protected $entity;
    protected $playerProposal;


    public function __construct(EntityManager $EntityManager, \DATA\DataBundle\Service\entity $entity, playerProposalAction $playerProposal)
    {
        $this->em = $EntityManager;
        $this->entity = $entity;
        $this->playerProposal = $playerProposal;
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

    public function getDifficultyLevel($playerProposal) {
        $difficultyLevelMax = 5;
        $difficultyLevelMin = 2;
        $difficultyLevelDefault = 3;
        $previousDifficultyLevel = null;
        $previousPlayerProposalChoice = null;

        $playerSession = $playerProposal->getPlayerOeuvre()->getPlayerSession();
        $previousPlayerOeuvres = $this->em->getRepository('CLICHESPlayerBundle:PlayerOeuvre')->findBy(array('playerSession' => $playerSession), array('createDate' => 'DESC'));

        if(count($previousPlayerOeuvres) <= 1) {
            return $difficultyLevelDefault;
        } else {
            foreach($previousPlayerOeuvres as $key => $previousPlayerOeuvre) {
                $playerProposalChoice = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposalChoice')->findOneBy(array('playerProposal' => $this->playerProposal->getPlayerProposalByPlayerOeuvre($previousPlayerOeuvre)));
                if($playerProposalChoice != null and $playerProposalChoice->getPlayerProposalChoiceValueSelected() != null) {
                    $previousDifficultyLevel = count($this->em->getRepository('CLICHESPlayerBundle:PlayerProposalChoiceValue')->findBy(array('playerProposalChoice' => $playerProposalChoice)));
                    $previousPlayerProposalChoice = $playerProposalChoice;

                    break;
                }
            }

            if($previousPlayerProposalChoice != null) {
                if ($previousPlayerProposalChoice->getCorrectAnswer() == true and $previousDifficultyLevel < $difficultyLevelMax) {
                    return $previousDifficultyLevel + 1;
                } elseif ($previousPlayerProposalChoice->getCorrectAnswer() == false and $previousDifficultyLevel > $difficultyLevelMin) {
                    return $previousDifficultyLevel - 1;
                } else {
                    return $previousDifficultyLevel;
                }
            } else {
                return $previousDifficultyLevel;
            }
        }
    }
}
