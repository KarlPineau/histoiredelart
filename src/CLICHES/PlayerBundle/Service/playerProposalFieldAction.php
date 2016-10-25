<?php

namespace CLICHES\PlayerBundle\Service;

use DATA\ImageBundle\Service\view;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class playerProposalFieldAction 
{
    protected $em;
    protected $security;
    protected $entity;
    protected $view;


    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, \DATA\DataBundle\Service\entity $entity, view $view)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->entity = $entity;
        $this->view = $view;
    }
    
    public function deletePlayerProposalField($playerProposalField)
    {
        $this->em->remove($playerProposalField);
        $this->em->flush();
    }
    
    public function foundFields($idImg)
    {
        set_time_limit(0);
        /* Méthodologie de la fonction :
         *  - On récupère l'id de l'image -> on récupère vue et tout
         *  - On génère un fichier JSON
         *  - Si la vue contient des éléments intéressants, on les intègre au JSON
         *  - On va chercher l'oeuvre concernée, on intègre les champs intéressants au JSON
         */
        
        $repositoryImage = $this->em->getRepository('DATAImageBundle:Image');
	    $image = $repositoryImage->findOneById($idImg);
        $view = $image->getView();

        $data = $this->entity->getFields($view->getEntity());
        $dataView = $this->view->getFieldsForView($view);


        return json_encode(array_merge($data, $dataView));
    }
    
    public function getByPlayerProposal($playerProposal)
    {
        set_time_limit(0);
        $repositoryPlayerProposalField = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField');
        return $repositoryPlayerProposalField->findByPlayerProposal($playerProposal);
    }

    public function getAverageFullByField($field=null)
    {
        set_time_limit(0);
        if($field == null) {
            $nullValue = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->countNull();
            $totalValue = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->countAll();
        } else {
            $nullValue = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->countNullByField($field);
            $totalValue = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->countAllByField($field);
        }
        return (($totalValue-$nullValue)*100)/$totalValue;
    }
}
