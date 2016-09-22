<?php

namespace DATA\DataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DATA\DataBundle\Form\ArtworkEditType;
use Symfony\Component\HttpFoundation\Request;

class ArtworkController extends Controller
{
    public function editAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryArtwork = $em->getRepository('DATADataBundle:Artwork');
        $artwork = $repositoryArtwork->findOneBySlug($slug);

        if ($artwork === null) {throw $this->createNotFoundException('Oeuvre : [slug='.$slug.'] inexistante.');}
        
        $form = $this->createForm(new ArtworkEditType, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artwork->setUpdateUser($this->getUser());
            $em->persist($artwork);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Félicitations, votre oeuvre a bien été éditée.' );
            return $this->redirect($this->generateUrl('data_data_entity_view', array('id' => $this->get('data_data.entity')->find('one', array('artwork' => $artwork), 'large')->getId())));
        }
        
        return $this->render('DATADataBundle:Artwork:Edit/edit.html.twig', array(
                                'entity' => $artwork,
                                'form' => $form->createView(),
                            ));
    }
}
