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
        $repositoryPlayerProposalField = $this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField');
        return $repositoryPlayerProposalField->findByPlayerProposal($playerProposal);
    }

    public function getPourcentageFullByField($field=null)
    {
        if($field == null) {
            $nullValue = count($this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->findBy(array('value' => null)));
            $totalValue = count($this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->findBy(array()));
        } else {
            $nullValue = count($this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->findBy(array('field' => $field, 'value' => null)));
            $totalValue = count($this->em->getRepository('CLICHESPlayerBundle:PlayerProposalField')->findBy(array('field' => $field)));
        }
        return ($totalValue-$nullValue)/$totalValue;
    }
}
