<?php

namespace DATA\DataBundle\Service;

use DATA\TeachingBundle\Service\teaching;
use Doctrine\ORM\EntityManager;
use DATA\DataBundle\Entity\Building;
use Symfony\Component\Security\Core\SecurityContext;

class artworkAction 
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

    public function getListFieldsForArtwork() {
        return [['field' => 'sujet', 'label' => 'Sujet / Titre'],
                ['field' => 'sujetIcono', 'label' => 'Sujet Iconographique'],
                ['field' => 'auteur', 'label' => 'Auteur'],
                ['field' => 'commanditaire', 'label' => 'Commanditaire'],
                ['field' => 'provenance', 'label' => 'Provenance'],
                ['field' => 'datation', 'label' => 'Datation'],
                ['field' => 'mattech', 'label' => 'Matières & Techniques'],
                ['field' => 'dimensions', 'label' => 'Dimensions'],
                ['field' => 'style', 'label' => 'Style / Mouvement'],
                ['field' => 'lieuDeConservation', 'label' => 'Lieu de Conservation']];
    }

    public function getItemPropForField($field)
    {
        if($field == 'sujet') {return 'name';}
        elseif($field == 'sujetIcono') {return '';}
        elseif($field == 'auteur') {return 'creator';}
        elseif($field == 'commanditaire') {return 'producer';}
        elseif($field == 'provenance') {return 'locationCreated';}
        elseif($field == 'datation') {return 'dateCreated';}
        elseif($field == 'mattech') {return 'artworkSurface';}
        elseif($field == 'dimensions') {return '';}
        elseif($field == 'style') {return 'genre';}
        elseif($field == 'lieuDeConservation') {return '';}

    }

    public function getFieldsForArtwork($artwork)
    {
        /* LISTE DES CHAMPS ARTWORK :
         * $sujet
         * $sujetIcono
         * $auteur
         * $commanditaire
         * $provenance
         * $datation
         * $mattech
         * $dimensions
         * $style
         * $lieuDeConservation
         */
        
        $data = array();
        if(!empty($artwork->getSujet())) {array_push($data, ['field' => 'sujet', 'label' => 'Sujet / Titre', 'value' => $artwork->getSujet()]);}
        if(!empty($artwork->getSujetIcono())) {array_push($data, ['field' => 'sujetIcono', 'label' => 'Sujet Iconographique', 'value' => $artwork->getSujetIcono()]);}
        if(!empty($artwork->getAuteur())) {array_push($data, ['field' => 'auteur', 'label' => 'Auteur', 'value' => $artwork->getAuteur()]);}
        if(!empty($artwork->getCommanditaire())) {array_push($data, ['field' => 'commanditaire', 'label' => 'Commanditaire', 'value' => $artwork->getCommanditaire()]);}
        if(!empty($artwork->getProvenance())) {array_push($data, ['field' => 'provenance', 'label' => 'Provenance', 'value' => $artwork->getProvenance()]);}
        if(!empty($artwork->getDatation())) {array_push($data, ['field' => 'datation', 'label' => 'Datation', 'value' => $artwork->getDatation()]);}
        if(!empty($artwork->getMattech())) {array_push($data, ['field' => 'mattech', 'label' => 'Matières & Techniques', 'value' => $artwork->getMattech()]);}
        if(!empty($artwork->getDimensions())) {array_push($data, ['field' => 'dimensions', 'label' => 'Dimensions', 'value' => $artwork->getDimensions()]);}
        if(!empty($artwork->getStyle())) {array_push($data, ['field' => 'style', 'label' => 'Style / Mouvement', 'value' => $artwork->getStyle()]);}
        if(!empty($artwork->getLieuDeConservation())) {array_push($data, ['field' => 'lieuDeConservation', 'label' => 'Lieu de Conservation', 'value' => $artwork->getLieuDeConservation()]);}
        
        return $data;
    }
    
    public function suggestFieldForArtwork($artwork, $excludeField)
    {
        if(empty($artwork->getDatation()) AND in_array("datation", $excludeField) == null) {return "datation";}
        elseif(empty($artwork->getSujetIcono()) AND in_array("sujetIcono", $excludeField) == null) {return "sujetIcono";}
        elseif(empty($artwork->getAuteur()) AND in_array("auteur", $excludeField) == null) {return "auteur";}
        elseif(empty($artwork->getLieuDeConservation()) AND in_array("lieuDeConservation", $excludeField) == null) {return "lieuDeConservation";}
        elseif(empty($artwork->getStyle()) AND in_array("style", $excludeField) == null) {return "style";}
        elseif(empty($artwork->getMattech()) AND in_array("mattech", $excludeField) == null) {return "mattech";}
        elseif(empty($artwork->getProvenance()) AND in_array("provenance", $excludeField) == null) {return "provenance";}
        elseif(empty($artwork->getCommanditaire()) AND in_array("commanditaire", $excludeField) == null) {return "commanditaire";}
        elseif(empty($artwork->getDimensions()) AND in_array("dimensions", $excludeField) == null) {return "dimensions";}
        else { return NULL;}
    }
    
}
