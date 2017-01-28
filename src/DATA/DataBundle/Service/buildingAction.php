<?php

namespace DATA\DataBundle\Service;

use DATA\TeachingBundle\Service\teaching;
use DATA\DataBundle\Entity\Artwork;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class buildingAction 
{
    protected $em;
    protected $security;
    protected $viewaction;
    protected $teaching;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, \DATA\ImageBundle\Service\viewAction $viewAction, teaching $teaching)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->viewaction = $viewAction;
        $this->teaching = $teaching;
    }

    public function getListFieldsForBuilding() {
        return [['field' => 'name', 'label' => 'Nom, Lieu', 'article' => 'le '],
                ['field' => 'auteur', 'label' => 'Auteur', 'article' => 'l\''],
                ['field' => 'commanditaire', 'label' => 'Commanditaire', 'article' => 'le '],
                ['field' => 'datation', 'label' => 'Datation', 'article' => 'la '],
                ['field' => 'mattech', 'label' => 'Matières & Techniques', 'article' => 'les '],
                ['field' => 'dimensions', 'label' => 'Dimensions', 'article' => 'les '],
                ['field' => 'style', 'label' => 'Style / Mouvement', 'article' => 'le ']];
    }

    public function getItemPropForField($field)
    {
        if($field == 'name') {return 'name';}
        elseif($field == 'auteur') {return 'creator';}
        elseif($field == 'commanditaire') {return 'producer';}
        elseif($field == 'datation') {return 'dateCreated';}
        elseif($field == 'mattech') {return 'artworkSurface';}
        elseif($field == 'dimensions') {return '';}
        elseif($field == 'style') {return 'genre';}

    }
    
    public function getFieldsForBuilding($building)
    {
        /* LISTE DES CHAMPS BUILDING :
         * $name
         * $auteur
         * $commanditaire
         * $datation
         * $mattech
         * $dimensions
         * $style
         */
        
        $data = array();
        if(!empty($building->getName())) {array_push($data, ['field' => 'name', 'label' => 'Nom, Lieu', 'value' => $building->getName()]);}
        if(!empty($building->getAuteur())) {array_push($data, ['field' => 'auteur', 'label' => 'Auteur', 'value' => $building->getAuteur()]);}
        if(!empty($building->getCommanditaire())) {array_push($data, ['field' => 'commanditaire', 'label' => 'Commanditaire', 'value' => $building->getCommanditaire()]);}
        if(!empty($building->getDatation())) {array_push($data, ['field' => 'datation', 'label' => 'Datation', 'value' => $building->getDatation()]);}
        if(!empty($building->getMattech())) {array_push($data, ['field' => 'mattech', 'label' => 'Matières & Techniques', 'value' => $building->getMattech()]);}
        if(!empty($building->getDimensions())) {array_push($data, ['field' => 'dimensions', 'label' => 'Dimensions', 'value' => $building->getDimensions()]);}
        if(!empty($building->getStyle())) {array_push($data, ['field' => 'style', 'label' => 'Style / Mouvement', 'value' => $building->getStyle()]);}
        
        return $data;
    }
    
    public function suggestFieldForBuilding($building, $excludeField)
    {
        if(empty($building->getDatation()) AND in_array("datation", $excludeField) == null) {return "datation";}
        elseif(empty($building->getAuteur()) AND in_array("auteur", $excludeField) == null) {return "auteur";}
        elseif(empty($building->getStyle()) AND in_array("style", $excludeField) == null) {return "style";}
        elseif(empty($building->getMattech()) AND in_array("mattech", $excludeField) == null) {return "mattech";}
        elseif(empty($building->getCommanditaire()) AND in_array("commanditaire", $excludeField) == null) {return "commanditaire";}
        elseif(empty($building->getDimensions()) AND in_array("dimensions", $excludeField) == null) {return "dimensions";}
        else { return NULL;}
    }
    
}
