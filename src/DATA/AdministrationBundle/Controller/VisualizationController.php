<?php

namespace DATA\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class VisualizationController extends Controller
{
    public function indexAction()
    {
        return $this->render('DATAAdministrationBundle:Visualization:index.html.twig');
    }

    public function getAction()
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $repositoryArtwork = $em->getRepository('DATADataBundle:Artwork');
            $repositoryBuilding = $em->getRepository('DATADataBundle:Building');

            $countArtwork = count($repositoryArtwork->findAll());
            $countBuilding = count($repositoryBuilding->findAll());

            $statArrayArtwork = array();
            $statArrayBuilding = array();
            
            foreach($this->get('data_data.artworkaction')->getListFieldsForArtwork() as $field) {
                $qb = $repositoryArtwork->createQueryBuilder('p');
                $qb->where('p.'.$field['field'].' IS NOT NULL');
                $countField = count($qb->getQuery()->getScalarResult());

                $statArrayArtwork[] = ['field' => $field['field'], 'current' => $countField, 'max' => $countArtwork];
            }

            foreach($this->get('data_data.buildingaction')->getListFieldsForBuilding() as $field) {
                $qb = $repositoryBuilding->createQueryBuilder('p');
                $qb->where('p.'.$field['field'].' IS NOT NULL');
                $countField = count($qb->getQuery()->getScalarResult());

                $statArrayBuilding[] = ['field' => $field['field'], 'current' => $countField, 'max' => $countBuilding];
            }

            $statArray = ['artwork' => $statArrayArtwork, 'building' => $statArrayBuilding];
            return new Response(json_encode($statArray));
        }


    }
}
