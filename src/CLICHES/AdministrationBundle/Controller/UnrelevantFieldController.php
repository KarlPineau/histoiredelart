<?php

namespace CLICHES\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UnrelevantFieldController extends Controller
{
    public function unrelevantFieldTraitementAction($unrelevantField_id)
    {
        $repositoryUnrelevantField = $this->getDoctrine()->getManager()
                                      ->getRepository('DATADataBundle:UnrelevantField');
        $unrelevantFields = $repositoryUnrelevantField->findOneById($unrelevantField_id);
        
        if ($unrelevantFields === null) {
          throw $this->createNotFoundException('UnrelevantField : [id='.$unrelevantFields.'] inexistant.');
        }
        
        $unrelevantFieldAction = $this->container->get('data_data.unrelevantfield');
        $unrelevantFieldAction->traitementUnrelevantField($unrelevantFields);
             
        $this->get('session')->getFlashBag()->add('notice', 'UnrelevantField a bien été notifié comme traité.' );
        return $this->redirectToRoute('cliches_administration_playersuggest_index');
    }


    public function unrelevantFieldValidateAction($unrelevantField_id)
    {
        $repositoryUnrelevantField = $this->getDoctrine()->getManager()->getRepository('DATADataBundle:UnrelevantField');
        $unrelevantFields = $repositoryUnrelevantField->findOneById($unrelevantField_id);

        if ($unrelevantFields === null) {
            throw $this->createNotFoundException('UnrelevantField : [id='.$unrelevantField_id.'] inexistant.');
        }

        $unrelevantFieldAction = $this->container->get('data_data.unrelevantfield');
        $unrelevantFieldAction->validateUnrelevantField($unrelevantFields);

        $this->get('session')->getFlashBag()->add('notice', 'UnrelevantField a bien été notifié comme validé.' );
        return $this->redirectToRoute('cliches_administration_playersuggest_index');
    }


    public function unrelevantFieldUnValidateAction($unrelevantField_id)
    {
        $repositoryUnrelevantField = $this->getDoctrine()->getManager()->getRepository('DATADataBundle:UnrelevantField');
        $unrelevantFields = $repositoryUnrelevantField->findOneById($unrelevantField_id);

        if ($unrelevantFields === null) {
            throw $this->createNotFoundException('UnrelevantField : [id='.$unrelevantField_id.'] inexistant.');
        }

        $unrelevantFieldAction = $this->container->get('data_data.unrelevantfield');
        $unrelevantFieldAction->unValidateUnrelevantField($unrelevantFields);

        $this->get('session')->getFlashBag()->add('notice', 'UnrelevantField a bien été notifié comme validé.' );
        return $this->redirectToRoute('cliches_administration_playersuggest_index');
    }


    public function unrelevantFieldRefuseAction($unrelevantField_id)
    {
        $repositoryUnrelevantField = $this->getDoctrine()->getManager()->getRepository('DATADataBundle:UnrelevantField');
        $unrelevantFields = $repositoryUnrelevantField->findOneById($unrelevantField_id);

        if ($unrelevantFields === null) {
            throw $this->createNotFoundException('UnrelevantField : [id='.$unrelevantField_id.'] inexistant.');
        }

        $unrelevantFieldAction = $this->container->get('data_data.unrelevantfield');
        $unrelevantFieldAction->refuseUnrelevantField($unrelevantFields);

        $this->get('session')->getFlashBag()->add('notice', 'UnrelevantField a bien été notifié comme validé.' );
        return $this->redirectToRoute('cliches_administration_playersuggest_index');
    }
}