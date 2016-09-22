<?php

namespace CLICHES\PlayerBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class playerResultAction 
{
    protected $em;
    protected $security;
    protected $entity;


    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, \DATA\DataBundle\Service\entity $entity)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->entity = $entity;
    }
    
    public function deletePlayerResult($playerResult)
    {
        $this->em->remove($playerResult);
        $this->em->flush();
    }
    
    public function foundResults($playerProposal_id, $jsonencode=true)
    {    
        $repositoryPlayerProposal = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposal');
	    $repositoryPlayerProposalField = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField');
	    $playerProposal = $repositoryPlayerProposal->findOneById($playerProposal_id);
        $playerProposalFields = $repositoryPlayerProposalField->findBy(array('playerProposal' => $playerProposal));
        
        $entity = $playerProposal->getPlayerOeuvre()->getView()->getEntity();


        $data = array();
        foreach ($playerProposalFields as $playerProposalField) {
            $trueValue = null;
            if ($playerProposalField->getField() == "vue" OR $playerProposalField->getField() == "iconography" OR $playerProposalField->getField() == "title" OR $playerProposalField->getField() == "location") {
                $getter = "get" . ucfirst($playerProposalField->getField());
                $trueValue = $playerProposal->getPlayerOeuvre()->getView()->$getter();
            } else {
                $trueValue = $this->entity->get($playerProposalField->getField(), $entity);
            }

            if ($trueValue != null) {
                $arrayField = [
                    ['field' => $playerProposalField->getField(),
                        'trueResult' => $trueValue,
                        'suggestResult' => $playerProposalField->getValue(),
                        'label' => $this->getLabel($playerProposalField->getField())]];
                $data = array_merge($data, $arrayField);
            }
        }

        if($jsonencode == true) {
            return json_encode( $data );
        } else {
            return $data;
        }
    }

    public function testFilled($playerProposal)
    {
        $repositoryPlayerProposalField = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField');
        $playerProposalFields = $repositoryPlayerProposalField->findBy(array('playerProposal' => $playerProposal));

        $filled = false;
        foreach ($playerProposalFields as $playerProposalField) {
            if($playerProposalField->getValue() != null) {$filled = true;}
        }

        return $filled;
    }

    public function getLabel($field)
    {
        switch ($field) {
            case 'name':
                return 'Nom';
                break;
            case 'sujet':
                return 'Sujet';
                break;
            case 'sujetIcono':
                return 'Sujet iconographique';
                break;
            case 'datation':
                return 'Datation';
                break;
            case 'auteur':
                return 'Auteur';
                break;
            case 'commanditaire':
                return 'Commanditaire';
                break;
            case 'lieuDeConservation':
                return 'Lieu de Conservation';
                break;
            case 'provenance':
                return 'Provenance';
                break;
            case 'mattech':
                return 'Matières & Techniques';
                break;
            case 'dimensions':
                return 'Dimensions';
                break;
            case 'style':
                return 'Style';
                break;
            case 'vue':
                return 'Vue';
                break;
            case 'iconography':
                return 'Iconographie de la vue';
                break;
            case 'title':
                return 'Titre de la vue';
                break;
            case 'location':
                return 'Emplacement de la vue';
                break;
        }
    }
}
