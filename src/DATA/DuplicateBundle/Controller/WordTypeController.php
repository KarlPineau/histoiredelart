<?php

namespace DATA\DuplicateBundle\Controller;

use DATA\DuplicateBundle\Entity\GlobalWordType;
use DATA\DuplicateBundle\Form\GlobalWordTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WordTypeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryType = $em->getRepository('DATADuplicateBundle:Type');
        $undefined = $repositoryType->findOneByName('undefined');

        $repositoryWordType = $em->getRepository('DATADuplicateBundle:WordType');
        $wordsTypeUndefined = $repositoryWordType->findByType($undefined);
        $globalWordType = new GlobalWordType();
        foreach($wordsTypeUndefined as $wordTypeUndefined) {
            $globalWordType->addWordType($wordTypeUndefined);
        }

        $form = $this->createForm(new GlobalWordTypeType(), $globalWordType);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                foreach($wordsTypeUndefined as $wordTypeUndefined) {
                    $em->persist($wordTypeUndefined);
                }
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Modifications effectuées' );
                return $this->redirect($this->generateUrl('data_duplicate_wordtype_index'));
            }
        }

        return $this->render('DATADuplicateBundle:WordType:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryWordType = $em->getRepository('DATADuplicateBundle:WordType');
        $words = $repositoryWordType->findBy(array(), array('word' => 'ASC'));

        return $this->render('DATADuplicateBundle:WordType:list.html.twig', array(
            'words' => $words,
        ));
    }
}
