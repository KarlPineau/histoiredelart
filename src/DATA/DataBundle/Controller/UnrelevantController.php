<?php

namespace DATA\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UnrelevantController extends Controller
{
    public function setUnrelevantAction($field, $id)
    {
        $entityService = $this->container->get('data_data.entity');
        $unrelevantService = $this->container->get('data_data.unrelevantfield');
        $entity = $entityService->getById($id);

        if ($entity === null) { throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.'); }

        $unrelevantService->setUnrelevant($field, $entity, null, true);

        $this->get('session')->getFlashBag()->add('notice', 'Félicitations, le champ a bien été qualifié comme non pertinent' );
        return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $entity->getId())));
    }

    public function setRelevantAction($unrelevantField_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entityService = $this->container->get('data_data.entity');
        $unrelevantService = $this->container->get('data_data.unrelevantfield');
        $repository = $em->getRepository('DATADataBundle:UnrelevantField');
        $entity = $entityService->getById($id);

        if ($entity === null) { throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.'); }
        
        $unrelevantFields = $repository->findOneBy(array('id' => $unrelevantField_id, 'entity' => $entity));
        if ($unrelevantFields === null) {throw $this->createNotFoundException('UnrelevantField : [id='.$unrelevantField_id.'] inexistant.');}

        $unrelevantService->deleteUnrelevantField($unrelevantFields);

        $this->get('session')->getFlashBag()->add('notice', 'Félicitations, le champ a bien été qualifié comme pertinent' );
        return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $entity->getId())));
    }
}
