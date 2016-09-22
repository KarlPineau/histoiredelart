<?php

namespace DATA\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TeachingTestController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryTeachingTest = $em->getRepository('DATATeachingBundle:TeachingTest');
        $repositoryTeachingTestVote = $em->getRepository('DATATeachingBundle:TeachingTestVote');
        $teachingTests = $repositoryTeachingTest->findAll();

        $teachingTestsArray = array();
        foreach($teachingTests as $teachingTest) {
            $teachingTestVotes = $repositoryTeachingTestVote->findBy(array('teachingTest' => $teachingTest));
            $teachingTestVotesOui = $repositoryTeachingTestVote->findBy(array('teachingTest' => $teachingTest, 'vote' => true));
            $teachingTestVotesNon = $repositoryTeachingTestVote->findBy(array('teachingTest' => $teachingTest, 'vote' => false));
            $teachingTestsArray[] = [
                'teachingTest' => $teachingTest,
                'teachingTestVotes' => $teachingTestVotes,
                'teachingTestVotesOui' => $teachingTestVotesOui,
                'teachingTestVotesNon' => $teachingTestVotesNon
            ];
        }

        return $this->render('DATAAdministrationBundle:TeachingTest:index.html.twig', array('teachingTests' => $teachingTestsArray));
    }

    public function lastVotesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryTeachingTestVote = $em->getRepository('DATATeachingBundle:TeachingTestVote');
        $votes = $repositoryTeachingTestVote->findBy(array(), array('createDate' => 'DESC'));

        $paginator  = $this->get('knp_paginator');
        $lastVotes = $paginator->paginate(
            $votes,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );

        return $this->render('DATAAdministrationBundle:TeachingTest:lastVotes.html.twig', array('lastVotes' => $lastVotes));
    }
}
