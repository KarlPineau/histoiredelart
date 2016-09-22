<?php

namespace TOOLS\NerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nameEntityRecognition = $em->getRepository('TOOLSNerBundle:NameEntityRecognition')->findBy(array('isVerified' => false));

        return $this->render('TOOLSNerBundle:Index:index.html.twig', array(
            'entities' => $nameEntityRecognition,
        ));
    }

    public function generateNameEntityRecognitionAction($field)
    {
        set_time_limit(0);
        $middleArray = array();
        foreach ($this->get('data_data.entity')->find('all', null, 'large') as $entity) {
            if($this->get('data_data.entity')->get($field, $entity) != null) {
                $middleArray[] = $entity;
            }
        }
        shuffle($middleArray);

        $count = 0;
        foreach($middleArray as $item) {
            if($this->getDoctrine()->getManager()->getRepository('TOOLSNerBundle:NameEntityRecognition')->findOneBy(array('usedIn' => $item, 'field' => $field)) == null AND $count < 100) {
                $return = $this->get('tools_ner.ner')->nameEntityRecognition($item->getId(), $field);
                if($return == false) {break;}
                else {
                    $count++;
                }
            }
        }

        return $this->redirectToRoute('tools_ner_index_index');
    }

    public function synsetsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('TOOLSNerBundle:NameEntityRecognition');

        $nameEntityRecognition = $repo->createQueryBuilder('t')
            ->where('t.synsets NOT LIKE \'s:2:"\\[\\]";\'')
            ->getQuery()
            ->getResult()
        ;


        return $this->render('TOOLSNerBundle:Index:index.html.twig', array(
            'entities' => $nameEntityRecognition,
        ));
    }

    public function noSynsetsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('TOOLSNerBundle:NameEntityRecognition');

        $nameEntityRecognition = $repo->createQueryBuilder('t')
            ->where('t.synsets LIKE \'s:2:"\\[\\]";\'')
            ->getQuery()
            ->getResult()
        ;


        return $this->render('TOOLSNerBundle:Index:index.html.twig', array(
            'entities' => $nameEntityRecognition,
        ));
    }

    public function noSynsetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('TOOLSNerBundle:NameEntityRecognition');

        $nameEntityRecognition = $repo->createQueryBuilder('t')
                    ->where('t.synset IS NULL')
                    ->andWhere('t.synsets NOT LIKE \'s:2:"\\[\\]";\'')
                    ->getQuery()
                    ->getResult()
        ;


        return $this->render('TOOLSNerBundle:Index:index.html.twig', array(
            'entities' => $nameEntityRecognition,
        ));
    }

    public function noSynsetHarvestAction()
    {
        set_time_limit(0);
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('TOOLSNerBundle:NameEntityRecognition');

        $nameEntityRecognitions = $repo->createQueryBuilder('t')
            ->where('t.synset IS NULL')
            ->andWhere('t.synsets NOT LIKE \'s:2:"\\[\\]";\'')
            ->getQuery()
            ->getResult()
        ;

        foreach($nameEntityRecognitions as $key => $nameEntityRecognition) {
            //if($key < 1) {
                $this->get('tools_ner.ner')->nameEntityRecognitionHarvest($nameEntityRecognition);
            //}
        }

        /*return $this->render('TOOLSNerBundle:Index:test.html.twig', array(
            'entity' => $return,
        ));*/
        return $this->redirectToRoute('tools_ner_index_noSynset');
    }
}
