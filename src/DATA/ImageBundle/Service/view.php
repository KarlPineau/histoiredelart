<?php

namespace DATA\ImageBundle\Service;

use DATA\DataBundle\Service\entity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

/*
 * Service dont l'objectif est d'appliquer des méthodes globales à toutes les entités de DATA
 */
class view 
{
    protected $em;
    protected $security;
    protected $entity;
    protected $viewAction;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, entity $entity, viewAction $viewAction)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->entity = $entity;
        $this->viewAction = $viewAction;
    }
    
    //Fonction retournant une oeuvre au hasard valable pour une session
    public function getRandView($session) 
    {
        $repositoryView = $this->em->getRepository('DATAImageBundle:View');
	    return $repositoryView->searchRandView($session);
    } 
    
    //Fonction retournant une oeuvre au hasard valable pour une session
    public function getViewsForArtwork($artwork) 
    {
        $repositoryView = $this->em->getRepository('DATAImageBundle:View');
	    return $repositoryView->findByArtwork($artwork);
    } 
    
    //Fonction retournant une oeuvre au hasard valable pour une session
    public function getViewsForBuilding($building) 
    {
        $repositoryView = $this->em->getRepository('DATAImageBundle:View');
	    return $repositoryView->findByBuilding($building);
    }

    public function getListFieldsForView() {
        return [['field' => 'isPlan', 'label' => 'Est-ce un plan ?'],
                ['field' => 'vue', 'label' => 'Vue'],
                ['field' => 'iconography', 'label' => 'Iconographie'],
                ['field' => 'title', 'label' => 'Titre'],
                ['field' => 'location', 'label' => 'Localisation']];
    }

    public function getFieldsForView($view)
    {
        /* LISTE DES CHAMPS ARTWORK :
         * $vue
         * $title
         * $iconography
         * $location
         */

        $data = array();
        if(!empty($view->getVue())) {array_push($data, ['field' => 'vue', 'label' => 'Vue', 'value' => $view->getVue()]);}
        if(!empty($view->getTitle())) {array_push($data, ['field' => 'title', 'label' => 'Vue', 'Titre' => $view->getTitle()]);}
        if(!empty($view->getIconography())) {array_push($data, ['field' => 'iconography', 'label' => 'Sujet iconographique', 'value' => $view->getIconography()]);}
        if(!empty($view->getLocation())) {array_push($data, ['field' => 'location', 'label' => 'Emplacement', 'value' => $view->getLocation()]);}

        return $data;
    }

    public function valueByField($field, $view) {
        if ($this->checkFieldForView($view, $field) != null) {
            return $this->checkFieldForView($view, $field);
        } else {
            if(!empty($view->get($field))) {
                if($view->get($field) === true) {
                    return 'Oui';
                } else {
                    return $view->get($field);
                }
            } else {
                return 'empty';
            }
        }
    }

    public function checkFieldForView($view, $field) {
        return $this->entity->checkField($this->entity->getByView($view), $field);
    }

    public function mergeViews($viewHost, $viewMerging) {
        /*
         * Liste des éléments à fusionner :
         *      - PlayerOeuvre Cliches
         *      - Votes
         *
         * Puis suppression de l'entité image correspondante et de l'entité view
         */

        $repositoryPlayerOeuvre = $this->em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        foreach ($repositoryPlayerOeuvre->findByView($viewMerging) as $playerOeuvre) {
            $playerOeuvre->setView($viewHost);
            $this->em->persist($playerOeuvre);
        }

        $repositoryTeachingTest = $this->em->getRepository('DATATeachingBundle:TeachingTest');
        $teachingTests = $repositoryTeachingTest->findByView($viewMerging);
        foreach ($teachingTests as $teachingTest) {
            $teachingTest->setView($viewHost);
            $this->em->persist($teachingTest);
        }

        $this->em->flush();

        $this->viewAction->deleteView($viewMerging);
    }
    
    public function getViewsForEntityAdmin($entity) {
        $views = $this->entity->getViews($entity);
        $viewArray = array();
        foreach ($views as $view) {
            $viewArray[] = ['view' => $view, 
                            'teachings' => $this->getTeachingsAdminForView($view), 
                            'proposals' => $this->em->getRepository('CLICHESPlayerBundle:PlayerOeuvre')->findByView($view)];
        }
        
        return $viewArray;
    }

    public function getTeachingsAdminForView($view)
    {
        $repositoryTeachingTest = $this->em->getRepository('DATATeachingBundle:TeachingTest');
        $repositoryTeachingTestVote = $this->em->getRepository('DATATeachingBundle:TeachingTestVote');

        $teachingArray = array();
        foreach($this->entity->getTeachings($this->entity->getByView($view)) as $teaching) {
            $teachingTests = $repositoryTeachingTest->findBy(array('view' => $view, 'teaching' => $teaching));

            $teachingTestsArray = array();
            foreach($teachingTests as $teachingTest) {
                $teachingTestsArray[] = [
                    'teachingTest' => $teachingTest,
                    'teachingTestVotes' => $repositoryTeachingTestVote->findBy(array('teachingTest' => $teachingTest)),
                    'teachingTestVotesOui' => $repositoryTeachingTestVote->findBy(array('teachingTest' => $teachingTest, 'vote' => true)),
                    'teachingTestVotesNon' => $repositoryTeachingTestVote->findBy(array('teachingTest' => $teachingTest, 'vote' => false))
                ];
            }
            $teachingArray[] = [
                'teaching' => $teaching,
                'teachingTests' => $teachingTestsArray
            ];
        }

        return $teachingArray;
    }

    public function checkOrderViews($views)
    {
        foreach($views as $key => $container)
        {
            $view = $container['view'];
            if($view->getOrderView() != ($key+1)) {
                $view->setOrderView($key+1);
                $this->em->persist($view);
            }
        }
        $this->em->flush();
    }
}
