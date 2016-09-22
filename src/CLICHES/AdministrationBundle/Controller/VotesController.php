<?php

namespace CLICHES\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class VotesController extends Controller
{
    public function statisticsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryTeachingTestVote = $em->getRepository('DATATeachingBundle:TeachingTestVote');
        $teachingTestVotes = $repositoryTeachingTestVote->findBy(array(), array('createDate' => 'DESC'), 20);
        $countTeachingTestVotes = count($repositoryTeachingTestVote->findAll());
        $countTeachingTestVotesYes = count($repositoryTeachingTestVote->findByVote(true));
        $countTeachingTestVotesNo = count($repositoryTeachingTestVote->findByVote(false));

        /* -- Calcul des erreurs potentielles -- */
        $potentialErrors = array();
        foreach($em->getRepository('DATAImageBundle:View')->findAll() as $view) {
            if(count($em->getRepository('DATATeachingBundle:TeachingTest')->findByView($view)) > 1) {
                $potentialErrors[] = $view;
            }
        }

        /* -- Calcul du TOP20 -- */
        $arrayCountVotesPerTeachingTest = array();
        foreach($em->getRepository('DATATeachingBundle:TeachingTest')->findAll() as $teachingTest) {
            $arrayCountVotesPerTeachingTest[$teachingTest->getId()] = count($repositoryTeachingTestVote->findByTeachingTest($teachingTest));
        }
        arsort($arrayCountVotesPerTeachingTest);

        /* -- Calcul du classement par teaching -- */
        $arrayTeachingRate = array();
        foreach($em->getRepository('DATATeachingBundle:Teaching')->findAll() as $teaching) {
            $numberVotes = 0;
            foreach($em->getRepository('DATATeachingBundle:TeachingTest')->findByTeaching($teaching) as $teachingTest) {
                $numberVotes += count($em->getRepository('DATATeachingBundle:TeachingTestVote')->findByTeachingTest($teachingTest));
            }
            $arrayTeachingRate[] = ['teaching' => $teaching, 'numberVote' => $numberVotes];
        }

        /* -- Calcul moyenne du nombre de vote / vues -- */
        $averageVoteView = count($em->getRepository('DATATeachingBundle:TeachingTestVote')->findAll())/count($em->getRepository('DATAImageBundle:View')->findAll());
        /* -- Calcul moyenne du nombre de vote / session Clichés -- */
        $averageVoteSession = count($em->getRepository('DATATeachingBundle:TeachingTestVote')->findAll())/count($em->getRepository('CLICHESPlayerBundle:PlayerSession')->findAll());


        return $this->render('CLICHESAdministrationBundle:Votes:statistics.html.twig', array(
            'teachingTestVotes' => $teachingTestVotes,
            'countTeachingTestVotes' => $countTeachingTestVotes,
            'top20' => $arrayCountVotesPerTeachingTest,
            'potentialErrors' => $potentialErrors,
            'arrayTeachingRate' => $arrayTeachingRate,
            'countTeachingTestVotesYes' => $countTeachingTestVotesYes,
            'countTeachingTestVotesNo' => $countTeachingTestVotesNo,
            'averageVoteView' => $averageVoteView,
            'averageVoteSession' => $averageVoteSession
        ));
    }

    public function normalizeVotesAction()
    {
        set_time_limit(0);
        $em = $this->getDoctrine()->getManager();
        $repositoryTeachingTestVote = $em->getRepository('DATATeachingBundle:TeachingTestVote');
        $repositoryTeachingTest = $em->getRepository('DATATeachingBundle:TeachingTest');
        $count = 0;

        foreach($em->getRepository('DATAImageBundle:View')->findAll() as $view) {
            foreach($em->getRepository('DATATeachingBundle:Teaching')->findAll() as $teaching) {
                $teachingTests = $repositoryTeachingTest->findBy(array('view' => $view, 'teaching' => $teaching));
                if(count($teachingTests) > 1) {
                    foreach($teachingTests as $key => $teachingTest) {
                        if($key > 0) {
                            foreach($repositoryTeachingTestVote->findByTeachingTest($teachingTest) as $vote) {
                                $vote->setTeachingTest($teachingTests[0]);
                                $em->persist($vote);
                                $count++;
                            }
                            $em->remove($teachingTest);
                            $em->flush();
                        }
                    }
                }
            }
        }

        $this->get('session')->getFlashBag()->add('notice', $count.' votes réattribués.');
        return $this->render('CLICHESAdministrationBundle:Votes:normalize.html.twig', array(
            'count' => $count,
        ));
    }

    public function getStatistiquesInTimeAction()
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $repositorySession = $em->getRepository('DATATeachingBundle:TeachingTestVote');
            $nowBegin = date("Y-m-d 00:00:00");
            $nowEnd = date("Y-m-d 23:59:59");

            $return = array();
            for($i = 0 ; $i <= 15 ; $i++)
            {
                //$nowBegin->sub(new \DateInterval('P'.$i.'D'));
                //$nowEnd->sub(new \DateInterval('P'.$i.'D'));
                $begin  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d")-$i, date("Y")));
                $end  = date("Y-m-d H:i:s", mktime(24, 59, 59, date("m")  , date("d")-$i, date("Y")));

                $players = $repositorySession->createQueryBuilder('p')
                    ->where('p.createDate BETWEEN :dayB AND :dayE')
                    ->setParameter('dayB', $begin)
                    ->setParameter('dayE', $end)
                    ->getQuery()->getResult();

                array_unshift($return, count($players));
            }

            return new Response(json_encode($return));
        }
    }
}
