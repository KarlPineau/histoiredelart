<?php

namespace DATA\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DATA\DataBundle\Form\BuildingEditType;
use Symfony\Component\HttpFoundation\Request;

class BuildingController extends Controller
{
    public function editAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryBuilding = $em->getRepository('DATADataBundle:Building');
        $building = $repositoryBuilding->findOneBySlug($slug);

        if ($building === null) {throw $this->createNotFoundException('Building : [slug='.$slug.'] inexistant.');}
        
        $form = $this->createForm(new BuildingEditType, $building);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $building->setUpdateUser($this->getUser());
            $em->persist($building);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, votre édifice a bien été éditée.' );
            return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $this->get('data_data.entity')->find('one', array('building' => $building), 'large')->getId())));
        }
        
        return $this->render('DATADataBundle:Building:Edit/edit.html.twig', array(
                                'entity' => $building,
                                'form' => $form->createView(),
                            ));
    }
}
