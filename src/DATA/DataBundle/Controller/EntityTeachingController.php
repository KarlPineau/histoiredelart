<?php

namespace DATA\DataBundle\Controller;

use DATA\DataBundle\Form\EntityTeachingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EntityTeachingController extends Controller
{
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('data_data.entity')->find('one', array('id' => $id), 'large');

        if ($entity === null) {throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.');}
        
        $form = $this->createForm(new EntityTeachingType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('teachings')->getData() as $teaching) {
                $teaching->addEntity($entity);
                $em->persist($teaching);
            }
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, l\'enseignement a bien été ajoutée.' );
            return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $entity->getId())));
        }
        
        return $this->render('DATADataBundle:Entity:Teaching/editTeaching.html.twig', array(
                                'entity' => $entity,
                                'form' => $form->createView(),
                            ));
    }
    
    public function deleteAction($id, $slug_teaching)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryTeaching = $em->getRepository('DATATeachingBundle:Teaching');
 
        $entity = $this->get('data_data.entity')->find('one', array('id' => $id), 'large');
        $teaching = $repositoryTeaching->findOneBySlug($slug_teaching);

        if($entity === null) {throw $this->createNotFoundException('Item : [id='.$id.'] inexistant.');}
        if($teaching === null) {throw $this->createNotFoundException('Teaching : [slug='.$slug_teaching.'] inexistant.');}

        $teaching->removeEntity($entity);
        $em->persist($teaching);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add('notice', 'Votre item a bien été supprimé de cet enseignement.' );
        return $this->redirectToRoute('data_data_entity_view', array('id' => $entity->getId()));
    }
}
