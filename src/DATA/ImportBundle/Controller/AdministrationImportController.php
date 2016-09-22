<?php

namespace DATA\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdministrationImportController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $this->get('data_data.entity')->find('all', array('importValidation' => false));

        if ($entities != null) {
            foreach ($entities as $entity) {
                if ($entity->getArtwork() == null AND
                    $entity->getBuilding() == null AND
                    $this->get('data_data.entity')->getViews($entity) == null
                ) {
                    $em->remove($entity);
                }
            }
            $em->flush();
            $entities = $this->get('data_data.entity')->find('all', array('importValidation' => false));
        }

        /* Rajouter la liste des dernières oeuvres insérées
         * Rajouter une fonction qui teste si on vient de valider la denière oeuvre d'une session d'import et
         * envoyer un mail à l'auteur avec : remerciement, la liste des oeuvres validées, la liste des oeuvres refusées, avec le motif
         * */
        
        return $this->render('DATAImportBundle:AdministrationImport:index.html.twig', array(
            'entities' => $entities
        ));
    }

    public function validationAction($id, $bool)
    {
        $em = $this->getDoctrine()->getManager();
        $entityRepository = $em->getRepository('DATADataBundle:Entity');

        $entity = $this->get('data_data.entity')->getById($id);
        if ($entity === null) { throw $this->createNotFoundException('Entité : [id='.$id.'] inexistante.'); }
        $session = $entity->getImportSession();
        $user = $entity->getCreateUser();

        if($bool == 'true') {
            $entity->setImportValidation(true);
            $em->persist($entity);
            $em->flush();
        } elseif($bool == 'false') {
            $this->get('data_data.entity')->removeEntity($entity);
        }

        /* -- Dans le cas où c'est la dernière oeuvre validée de la session, on envoie un mail -- */
        $validBool = true;
        foreach($this->get('data_data.entity')->find('all', array('importSession' => $session)) as $entitySession) {
            if($entitySession->getImportValidation() == false) {$validBool = false;}
        }
        if($validBool == true) {
            $session->setImportValidation(true);
            $em->persist($session);
            $em->flush();

            if($em->getRepository('CASUserBundle:UserPreferences')->findOneByUser($user)->getDataDatasetConfirmation() == true) {
                /* -- Dans le cas où l'utilisateur souhaite être notifié des validations, on lui envoie un mail -- */
                $message = \Swift_Message::newInstance()
                    ->setSubject('Merci d\'avoir contribué à DATA !')
                    ->setFrom('cliches@histoiredelart.fr')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'DATAImportBundle:AdministrationImport:mailthanks.html.twig',
                            array('name' => $user->getUsername(), 'entities' => $this->get('data_data.entity')->find('all', array('importSession' => $session)))
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }
        }

        return $this->redirectToRoute('data_import_administrationimport_index');
    }

    public function archivesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryEntityImportSession = $em->getRepository('DATAImportBundle:EntityImportSession');
        $entitiesImportSession = $repositoryEntityImportSession->findBy(array('importValidation' => true));

        return $this->render('DATAImportBundle:AdministrationImport:archives.html.twig', array(
            'entitiesImportSession' => $entitiesImportSession,
        ));
    }
}
