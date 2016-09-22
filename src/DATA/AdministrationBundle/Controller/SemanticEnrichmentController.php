<?php

namespace DATA\AdministrationBundle\Controller;


use DATA\DataBundle\Entity\SemanticEnrichment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SemanticEnrichmentController extends Controller
{
    public function generateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nameEntityRecognitionRepository = $em->getRepository('TOOLSNerBundle:NameEntityRecognition');
        $semanticEnrichmentRepository = $em->getRepository('DATADataBundle:SemanticEnrichment');

        $count = 0;
        foreach($nameEntityRecognitionRepository->findAll() as $ner) {
            if($ner->getUri() != null AND
                $semanticEnrichmentRepository->findOneBy(array('entity' => $ner->getUsedIn(), 'field' => $ner->getField())) == null
            ) {
                $semanticEnrichment = new SemanticEnrichment();
                $semanticEnrichment->setEntity($ner->getUsedIn());
                $semanticEnrichment->setField($ner->getField());
                $semanticEnrichment->setUri($ner->getUri());
                $em->persist($semanticEnrichment);
                $em->flush();
                $count++;
            }
        }

        $this->get('session')->getFlashBag()->add('notice', 'Félicitations, '.$count.' entitiés ont été générées.' );
        return $this->redirectToRoute('data_administration_home_index');
    }
}
