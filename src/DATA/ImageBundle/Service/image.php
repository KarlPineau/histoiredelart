<?php

namespace DATA\ImageBundle\Service;

use DATA\DataBundle\Service\entity;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

/*
 * Service dont l'objectif est d'appliquer des méthodes globales à toutes les entités de DATA
 */
class image 
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
    public function getOneByView($view)
    {
        $repositoryImage = $this->em->getRepository('DATAImageBundle:Image');
	    return $repositoryImage->findOneByView($view);
    }

    public function getTitle($image)
    {
        $title = "";

        if($image->getView() != null) {
            if (!empty($image->getView()->getTitle())) {
                $title = $image->getView()->getTitle();
            } elseif (!empty($this->entity->getName($this->entity->getByView($image->getView())))) {
                $title = $this->entity->getName($this->entity->getByView($image->getView()));
            }
        }

        return $title;
    }
}
