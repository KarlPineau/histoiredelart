<?php

namespace DATA\DuplicateBundle\Controller;

use DATA\DuplicateBundle\Entity\Unmatch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DuplicateController extends Controller
{
    public function indexAction()
    {
        set_time_limit(0);
        $serviceAnalysis = $this->container->get('data_duplicate.analysis');
        $serviceEntity = $this->container->get('data_data.entity');
        $returnArray = array();

        if(isset($_GET['id']) AND !empty($_GET['id'])) {
            $entity = $serviceEntity->getById($_GET['id']);
            if($entity === null) {throw $this->createNotFoundException('Item : [id='.$_GET['id'].'] inexistant.');}

            $duplicates = $serviceAnalysis->globalAnalysis('', $entity);
            if($duplicates != null) {
                $returnArray[] = ['entity' => $entity,
                    'duplicates' => $duplicates];
            }
        } else {
            $entities = $serviceEntity->find('all', null, 'large');
            shuffle($entities); //Mélange les éléments (pour ne pas tomber tjs sur le même résultat)

            $count = 0;
            foreach($entities as $entity) {
                $count++; if($count > 1) {break;}

                $duplicates = $serviceAnalysis->globalAnalysis('', $entity);
                if($duplicates != null) {
                    $returnArray[] = ['entity' => $entity,
                        'duplicates' => $duplicates];
                }
            }
        }

        return $this->render('DATADuplicateBundle:Duplicate:index.html.twig', array('return' => $returnArray));
    }

    public function unmatchAction($entityLeft_id, $entityRight_id)
    {
        $em = $this->getDoctrine()->getManager();
        $entityService = $this->container->get('data_data.entity');

        $unmatch = new Unmatch();
        $left = $entityService->find('one', array('id' => $entityLeft_id), 'large');
        if ($left === null) { throw $this->createNotFoundException('Erreur : au moins une entité inexistante.'); }
        $unmatch->setEntityLeft($left);

        $right = $entityService->find('one', array('id' => $entityRight_id), 'large');
        if ($right === null) { throw $this->createNotFoundException('Erreur : au moins une entité inexistante.'); }
        $unmatch->setEntityRight($right);

        $unmatch->setUnmatch(true);
        $em->persist($unmatch);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Unmatch enregistré' );
        return $this->redirect($this->generateUrl('data_duplicate_duplicate_index'));
    }

    public function mergeDuplicatesAction($entityLeft_id, $entityRight_id)
    {
        //EntityRight est fusionnée dans EntityLeft
        $mergeDuplicatesService = $this->container->get('data_duplicate.mergeduplicates');
        $left = $mergeDuplicatesService->mergeDuplicates($entityLeft_id, $entityRight_id);

        $this->get('session')->getFlashBag()->add('notice', 'Doublons fusionnés' );
        return $this->redirect($this->generateUrl('data_duplicate_duplicate_index'));
    }
}
