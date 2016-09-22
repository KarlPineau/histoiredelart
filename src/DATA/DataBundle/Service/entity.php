<?php

namespace DATA\DataBundle\Service;

use DATA\DataBundle\Entity\Artwork;
use DATA\DataBundle\Entity\Building;
use DATA\ImageBundle\Service\viewAction;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class entity 
{
    protected $em;
    protected $security;
    protected $artwork;
    protected $building;
    protected $viewaction;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, artworkAction $artwork, buildingAction $building, viewAction $viewaction)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->artwork = $artwork;
        $this->building = $building;
        $this->viewaction = $viewaction;
    }
    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- Find                --------------------------------------------- */
    public function find($number=null, $properties=null, $scope=null) {
        $repositoryEntity = $this->em->getRepository('DATADataBundle:Entity');

        if($scope == 'restrict') {$properties['importValidation'] = true;}
        if($properties == null) {$properties = array();}

        if($number == 'all' OR $number == 'array') {
            return $repositoryEntity->findBy($properties);
        }

        elseif($number == 'one') {
            return $repositoryEntity->findOneBy($properties);
        }

        else {
            return false;
        }
    }

    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- GET BY              --------------------------------------------- */
    public function getByView($view) {
        if(!empty($view->getEntity())) {return $view->getEntity();}
    }

    public function getByTeaching($teaching, $scope=null) {
        if($scope == 'restrict') {
            $return = array();

            foreach ($teaching->getEntities() as $entity) {
                if($entity->getImportValidation() != false) {
                    $return[] = $entity;
                }
            }
            return $return;
        } else {
            return $teaching->getEntities();
        }
    }

    public function getByPure($entity) {
        if($entity->getArtwork() != null) {return $entity->getArtwork();}
        elseif($entity->getBuilding() != null) {return $entity->getBuilding();}
    }

    public function getBySlug($slug) {
        $repositoryArtwork = $this->em->getRepository('DATADataBundle:Artwork');
        $repositoryBuilding = $this->em->getRepository('DATADataBundle:Building');

        if($repositoryArtwork->findOneBySlug($slug) != null) {return $repositoryArtwork->findOneBySlug($slug);}
        elseif($repositoryBuilding->findOneBySlug($slug) != null) {return $repositoryBuilding->findOneBySlug($slug);}
    }
    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- GET PURE BY         --------------------------------------------- */
    public function getById($entityId) {
        return $this->find('one', array('id' => $entityId), 'large');
    }
    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- GET PURE            --------------------------------------------- */
    public function getPureByView($view) {
        if(!empty($view->getArtwork())) {
            return $this->find('one', ['artwork' => $view->getArtwork()], 'large');
        }
        elseif(!empty($view->getBuilding())) {
            return $this->find('one', ['building' => $view->getBuilding()], 'large');
        }
    }

    public function getPure($entity) {
        if($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Artwork') {
            return $this->find('one', array('artwork' => $entity), 'large');
        } elseif($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Building') {
            return $this->find('one', array('building' => $entity), 'large');
        }
    }
    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- GET INFORMATION     --------------------------------------------- */
    public function getSlug($entity, $view=NULL) {
        if($view != NULL) {$entity = $this->getByView($view);}
        
        if($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Entity') {
            if($entity->getArtwork() != null) {return $entity->getArtwork()->getSlug();}
            elseif($entity->getBuilding() != null) {return $entity->getBuilding()->getSlug();}
        }
    }
    
    public function getName($entity, $view=NULL) {
        if($view != NULL) {$entity = $this->getByView($view);}
        
        if($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Entity') {
            if($entity->getArtwork() != null) {return $entity->getArtwork()->getSujet();}
            elseif($entity->getBuilding() != null) {return $entity->getBuilding()->getName();}
        }
    }

    public function get($field, $entity) {
        if($entity->getArtwork() != null) {return $entity->getArtwork()->get($field);}
        elseif($entity->getBuilding() != null) {return $entity->getBuilding()->get($field);}
    }

    public function getType($entity) {
        //Garder la condition au cas où on testerait la fonction avec une valeur autre que Entité Pure
        if($this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName() == 'DATA\DataBundle\Entity\Entity') {
            if($entity->getArtwork() != null) {return 'artwork';}
            elseif($entity->getBuilding() != null) {return 'building';}
        }
    }
    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- SETTER              --------------------------------------------- */
    public function set($field, $entity, $value) {
        if($entity->getArtwork() != null) {$subEntity = $entity->getArtwork();}
        elseif($entity->getBuilding() != null) {$subEntity = $entity->getBuilding();}
        
        $subEntity->set($field, $value);
        $this->em->persist($subEntity);
        $this->em->flush();
    }   
    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- GET MODULES         --------------------------------------------- */
    public function getViews($entity) {
        return $this->em->getRepository('DATAImageBundle:View')->findBy(array('entity' => $entity), array('orderView' => 'ASC'));
    }

    public function getTeachings($entity) {
        $repositoryView = $this->em->getRepository('DATATeachingBundle:Teaching');
        $teachings = $repositoryView->findAll();

        $returnTeachings = array();
        foreach ($teachings as $teaching) {
            foreach ($teaching->getEntities() as $entityTeaching) {
                if($entityTeaching == $entity)
                {
                    array_push($returnTeachings, $teaching);
                }
            }
        }

        return $returnTeachings;
    }

    public function getTeachingTestsForEntity($entity, $teaching) {
        $repositoryTeachingTest = $this->em->getRepository('DATATeachingBundle:TeachingTest');
        return $repositoryTeachingTest->findBy(array('view' => $this->getViews($entity), 'teaching' => $teaching));
    }

    public function getSources($entity) {
        return $this->em->getRepository('DATADataBundle:Source')->findByEntity($entity);
    }

    public function getSameAs($entity, $view=null) {
        if($view != NULL) {$entity = $this->getByView($view);}
        return $this->em->getRepository('DATADataBundle:SameAs')->findByEntity($entity);
    }

    /* see also getSemanticEnrichment for single result */
    public function getSemanticEnrichments($entity) {
        return $this->em->getRepository('DATADataBundle:SemanticEnrichment')->findByEntity($entity);
    }
    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- VERIFICATION        --------------------------------------------- */
    public function isPropertyExist($entity, $field)
    {
        if($entity->getArtwork() != null) {
            return property_exists($entity->getArtwork(), $field);
        }
        if($entity->getBuilding() != null) {
            return property_exists($entity->getBuilding(), $field);
        }
    }
    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- ENTITY ACTIONS      --------------------------------------------- */
    public function removeEntity($entity, $left=null) {
        /*
         * La présence d'un left entraine une fusion et non une suppression
         *
         * Liste des modules à supprimer pour une entité :
         *      - Entity <- Check
         *      - Artwork ou Building <- Check
         *      - Views <- Check
         *      - Teaching <- Check
         *      - Sources <- Chech
         *      - SameAs <- Check
         *      - Pad <- Check
         *      - Visit <- Check
         *      - Unmatch <- Check
         *      - Reporting <- Check
         *      - Data favorite <- Check
         *      - Name Entity Recognition <- Check
         *      - SemanticEnrichment <- Check
         */

        foreach ($this->getSemanticEnrichments($entity) as $semanticEnrichment) {
            //Pas de left dans ce cas car SemanticEnrichments dépend de la sub-entity qui est delete dans tous les cas
            $this->em->delete($semanticEnrichment);
        }
        foreach ($this->em->getRepository('TOOLSNerBundle:NameEntityRecognition')->findByUsedIn($entity) as $nameEntityRecognition) {
            //Pas de left dans ce cas car NameEntityRecognition dépend de la sub-entity qui est delete dans tous les cas
            $this->em->remove($nameEntityRecognition);
        }
        foreach ($this->getViews($entity) as $view) {
            if($left != null) {$view->setEntity($left); $this->em->persist($view);}
            else {$this->viewaction->deleteView($view);}
        }
        foreach($this->getSources($entity) as $source) {
            if($left != null) {$source->setEntity($left); $this->em->persist($source);}
            else {$this->em->remove($source);}
        }
        foreach ($this->em->getRepository('DATAPublicBundle:Reporting')->findByEntity($entity) as $reporting) {
            if($left != null) {$reporting->setEntity($left); $this->em->persist($reporting);}
            else {$this->em->remove($reporting);}
        }
        foreach ($this->em->getRepository('DATAPublicBundle:Visit')->findByEntity($entity) as $visit) {
            if($left != null) {$visit->setEntity($left); $this->em->persist($visit);}
            $this->em->remove($visit);
        }
        foreach ($this->getSameAs($entity) as $sameAs) {
            if($left != null) {$sameAs->setEntity($left); $this->em->persist($sameAs);}
            $this->em->remove($sameAs);
        }
        foreach ($this->em->getRepository('DATADataBundle:Pad')->findByEntity($entity) as $pad) {
            if($left != null and $this->em->getRepository('DATADataBundle:Pad')->findOneByEntity($left) == null) {
                //Si LEFT a déjà un pad, on supprime celui de right, sinon on fusionne
                $pad->setEntity($left);$this->em->persist($pad);
            } else {
                $this->em->remove($pad);
            }
        }
        foreach ($this->em->getRepository('CASUserBundle:Favorite')->findByEntity($entity) as $favorite) {
            if($left != null and $this->em->getRepository('CASUserBundle:Favorite')->findOneBy(array('entity' => $entity, 'user' => $favorite->getUser())) == null) {
                $favorite->setEntity($left);
                $this->em->persist($favorite);
            } else {
                $this->em->remove($favorite);
            }
        }

        $repositoryUnmatch = $this->em->getRepository('DATADuplicateBundle:Unmatch');
        $matchLeft = $repositoryUnmatch->findByEntityLeft($entity);
        $matchRight = $repositoryUnmatch->findByEntityRight($entity);
        if($left != null) {
            foreach ($matchRight as $match) {$match->setEntityRight($left); $this->em->persist($match);}
            foreach ($matchLeft as $match) {$match->setEntityLeft($left); $this->em->persist($match);}
        } else {
            $matchs = array_merge($matchLeft, $matchRight);
            foreach ($matchs as $match) {
                $this->em->remove($match);
            }
        }

        if($entity->getArtwork() != null) {
            //On supprime dans tous les cas car si doublon, on garde celui de left
            $this->em->remove($entity->getArtwork());
        }
        if($entity->getBuilding() != null) {
            //On supprime dans tous les cas car si doublon, on garde celui de left
            $this->em->remove($entity->getBuilding());
        }

        foreach($this->getTeachings($entity) as $teaching) {
            $teaching->removeEntity($entity);
            //On n'ajoute que si le teaching n'est pas déjà attribué à left
            if($left != null) {
                $alreadyDefined = false;
                foreach ($this->getTeachings($left) as $teachingLeft) {
                    if($teachingLeft == $teaching) {
                        $alreadyDefined = true;
                    }
                }
                if($alreadyDefined == false) {$teaching->addEntity($left);}
            }
            $this->em->persist($teaching);
        }

        $this->em->remove($entity);
        $this->em->flush();
    }

    public function switchTo($entity)
    {
        if($this->getType($entity) == "artwork") {
            $newSubEntity = new Building();
            $newSubEntity->setMattech($this->get('mattech', $entity));
            $newSubEntity->setCommanditaire($this->get('commanditaire', $entity));
            $newSubEntity->setAuteur($this->get('auteur', $entity));
            $newSubEntity->setDatation($this->get('datation', $entity));
            $newSubEntity->setDimensions($this->get('dimensions', $entity));
            $newSubEntity->setName($this->get('sujet', $entity));
            $newSubEntity->setStyle($this->get('style', $entity));
            $this->em->persist($newSubEntity);
            $this->artwork->deleteArtwork($entity->getArtwork());
            $entity->setBuilding($newSubEntity);
            
        } elseif($this->getType($entity) == "building") {
            $newSubEntity = new Artwork();
            $newSubEntity->setMattech($this->get('mattech', $entity));
            $newSubEntity->setCommanditaire($this->get('commanditaire', $entity));
            $newSubEntity->setAuteur($this->get('auteur', $entity));
            $newSubEntity->setDatation($this->get('datation', $entity));
            $newSubEntity->setDimensions($this->get('dimensions', $entity));
            $newSubEntity->setSujet($this->get('name', $entity));
            $newSubEntity->setStyle($this->get('style', $entity));
            $this->em->persist($newSubEntity);
            $this->building->deleteBuilding($entity->getBuilding());
            $entity->setArtwork($newSubEntity);
        }
    }
    /* -------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------- */
    /* -------------------------------------------- FIELD MANAGMENT     --------------------------------------------- */
    public function getFields($entity) {
        if($entity->getArtwork() != null) {return $this->artwork->getFieldsForArtwork($entity->getArtwork());}
        elseif($entity->getBuilding() != null) {return $this->building->getFieldsForBuilding($entity->getBuilding());}
    }
    
    public function suggestField($view, $excludeField=array()) {
        if($view != NULL) {
            $entity = $this->getByView($view);

            if($entity->getArtwork() != null) {
                $return = $this->artwork->suggestFieldForArtwork($entity->getArtwork(), $excludeField);
            } elseif($entity->getBuilding() != null) {
                $return = $this->building->suggestFieldForBuilding($entity->getBuilding(), $excludeField);
            } else {
                $return = null;
            }

            if($return != null) {
                if ($this->checkField($entity, $return) == null) {
                    return $return;
                } else {
                    $excludeField[] = $return;
                    return $this->suggestField($view, $excludeField);
                }
            } else { return null; }
        } else { return null; }
    }

    public function checkField($entity, $field) {
        return $this->em->getRepository('DATADataBundle:UnrelevantField')->findOneBy(array('entity' => $entity, 'field' => $field, 'confirmed' => true));
    }

    public function valueByField($field, $entity) {
        if(($entity->getArtwork() != null AND property_exists($entity->getArtwork(), $field) == false) OR
           ($entity->getBuilding() != null AND property_exists($entity->getBuilding(), $field) == false)) {
            return 'noproperty';
        } elseif ($this->checkField($entity, $field) != null) {
            return 'unrelevant';
        } else {
            if($this->get($field, $entity) != null) {
                return $this->get($field, $entity);
            } else {
                return 'empty';
            }
        }
    }

    public function getListFieldsForEntity($entity) {
        if($entity->getArtwork() != null) {
            return $this->artwork->getListFieldsForArtwork();
        } elseif($entity->getBuilding() != null) {
            return $this->building->getListFieldsForBuilding();
        }
    }

    public function getItemPropForField($entity, $field) {
        if($entity->getArtwork() != null) {
            return $this->artwork->getItemPropForField($field);
        } elseif($entity->getBuilding() != null) {
            return $this->building->getItemPropForField($field);
        }
    }

    public function isSujetAsIconography($entity) {
        if($this->em->getRepository('DATADataBundle:SujetAsIconography')->findOneByEntity($entity) != null) {
            return true;
        } else { return false; }
    }

    /* see also getSemanticEnrichments for all results */
    public function getSemanticEnrichment($field, $entity) {
        $semanticEnrichment = $this->em->getRepository('DATADataBundle:SemanticEnrichment')->findOneBy(array('field' => $field, 'entity' => $entity));
        if($semanticEnrichment != null) {
            return $semanticEnrichment->getUri();
        } else {
            return null;
        }
    }
    /* -------------------------------------------------------------------------------------------------------------- */
}
