<?php

namespace DATA\ImportBundle\Controller;

use DATA\DataBundle\Entity\Artwork;
use DATA\DataBundle\Entity\Building;
use DATA\DataBundle\Entity\Entity;
use DATA\ImportBundle\Entity\EntityImportSession;
use DATA\ImportBundle\Form\EntityImportType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ImportController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entityImportSession = new EntityImportSession();
        $entityImportSession->setCreateUser($this->getUser());
        $entityImportSession->setImportValidation(false);
        $em->persist($entityImportSession);

        $entity = new Entity();
        $entity->setCreateUser($this->getUser());
        $entity->setImportSession($entityImportSession);
        $entity->setImportValidation(false);
        $em->persist($entity);

        $em->flush();
        
        return $this->render('DATAImportBundle:Import:index.html.twig', array('entityImportSession' => $entityImportSession, 'entity' => $entity));
    }
    
    public function importAction($import_session_id, $entity_id)
    {
        $em = $this->getDoctrine()->getManager();
        $entityImportSession = $em->getRepository('DATAImportBundle:EntityImportSession')->findOneById($import_session_id);
        if ($entityImportSession === null) { throw $this->createNotFoundException('Session : [id='.$import_session_id.'] inexistante.'); }
        $entity = $this->get('data_data.entity')->getById($entity_id);
        if ($entity === null) { throw $this->createNotFoundException('Item : [id='.$entity.'] inexistant.'); }
        if ($entity->getImportSession() != $entityImportSession) { throw $this->createNotFoundException('Mauvaise correspondance.'); }


        $form = $this->createForm(new EntityImportType(), $entity);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $type = $form->get('type')->getData();
                $image = $form->get('image')->getData();

                /* -- Création de la sous-entité : -- */
                if($type == 'artwork') { $subEntity = new Artwork(); $entity->setArtwork($subEntity); }
                elseif($type == 'building') { $subEntity = new Building(); $entity->setBuilding($subEntity); }
                foreach ($form->get('fields')->getData() as $field) {$this->get('data_data.entity')->set($field->getField(), $entity, $field->getValue());}
                $subEntity->setCreateUser($this->getUser());
                $em->persist($subEntity);

                /* -- Ajout de l'enseignement-- */
                foreach ($form->get('teachings')->getData() as $teaching) {
                    $teaching->addEntity($entity);
                    $em->persist($teaching);
                }

                /* -- Ajout des sources -- */
                foreach ($form->get('sources')->getData() as $source) {
                    $source->setEntity($entity);
                    $em->persist($source);
                }

                /* -- Ajout de la vue -- */
                $image->getView()->setEntity($entity);
                $em->persist($image);

                $em->persist($entity);
                $em->flush();

                // Chargement de la prochaine entité :
                $continueEntity = new Entity();
                $continueEntity->setCreateUser($this->getUser());
                $continueEntity->setImportSession($entityImportSession);
                $continueEntity->setImportValidation(false);
                $em->persist($continueEntity);
                $em->flush();

                return $this->redirect($this->generateUrl('data_import_import_import', array(
                    'import_session_id' => $import_session_id,
                    'entity_id' => $continueEntity->getId())));
            }
        }

        $dataset = $this->get('data_data.entity')->find('all', ['importSession' => $entityImportSession]);
        $datasetSize = count($dataset)-1;
        $totalDatabase = count($this->get('data_data.entity')->find('all'));

        return $this->render('DATAImportBundle:Import:import.html.twig', array(
            'import_session_id' => $import_session_id,
            'form' => $form->createView(),
            'entity' => $entity,
            'entityImportSession' => $entityImportSession,
            'datasetSize' => $datasetSize,
            'dataset' => $dataset,
            'totalDatabase' => $totalDatabase
        ));
    }

    public function endAction($import_session_id)
    {
        //ICI ! Nécessité de cleaner les données -> On teste toutes les entités de la base qui ne sont pas validées,
        // si certaines sont totalement vides (sans artwork ou building, ou view liée), on les delete
        //Il sera probablement nécessaire de le faire ailleurs aussi 
        //  -> par exemple en page d'accueil de data ou de l'admin de data quand on vient de importAction
        
        
        //Ca pourrait aussi être bien de récupéré toutes les entités importées par l'user pour lui les montrer
        return $this->render('DATAImportBundle:Import:end.html.twig');
    }

    public function entityFieldAction($type)
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()) {
            if($type == "artwork") {return new Response(json_encode($this->get('data_data.artworkaction')->getListFieldsForArtwork()));}
            elseif($type == "building") {return new Response(json_encode($this->get('data_data.buildingaction')->getListFieldsForBuilding()));}
        }

    }
}
