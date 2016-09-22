<?php

namespace DATA\ImageBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

/*
 * Service dont l'objectif est d'appliquer des méthodes globales à toutes les entités de DATA
 */
class viewAction 
{
    protected $em;
    protected $security;
    protected $playerOeuvre;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, \CLICHES\PlayerBundle\Service\playerOeuvreAction $playerOeuvre)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->playerOeuvre = $playerOeuvre;
    } 
    
    public function deleteView($view)
    {
        /*
         * Liste des modules à supprimer pour une entité :
         *      - Image <- Check
         *      - ExcludeView <- Check
         *      - TeachingTest <- Check
         *      - TeachingTestVote <- Check
         *      - PlayerSuggest <- Check
         *      - PlayerOeuvre <- Check
         */
        
        $repositoryImage = $this->em->getRepository('DATAImageBundle:Image');
        $image = $repositoryImage->findOneByView($view);
        if($image != null) {$this->em->remove($image);}
        
        foreach ($this->em->getRepository('CLICHESPlayerBundle:PlayerOeuvre')->findByView($view) as $playerOeuvre) {$this->playerOeuvre->deletePlayerOeuvre($playerOeuvre);}
        foreach ($this->em->getRepository('CLICHESPlayerBundle:PlayerSuggest')->findByView($view) as $item) {$this->em->remove($item);}

        $repositoryExcludeView = $this->em->getRepository('CLICHESPlayerBundle:ExcludeView');
        $excludeView = $repositoryExcludeView->findOneByView($view);
        if($excludeView != null) {$this->em->remove($excludeView);}

        $repositoryTeachingTest = $this->em->getRepository('DATATeachingBundle:TeachingTest');
        $repositoryTeachingTestVote = $this->em->getRepository('DATATeachingBundle:TeachingTestVote');
        $teachingTests = $repositoryTeachingTest->findByView($view);
        foreach ($teachingTests as $teachingTest) {
            $votes = $repositoryTeachingTestVote->findBy(array('teachingTest' => $teachingTest));
            foreach($votes as $vote) {$this->em->remove($vote);}
            $this->em->remove($teachingTest);
        }
        
        $this->em->remove($view);
        $this->em->flush();
    }
}
