<?php

namespace CLICHES\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class StatisticsController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositorySession = $em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $nbSessions = count($repositorySession->findAll());
        $nbSimpleSessions = count($repositorySession->findBySimpleSession(true));

        /* -- Calcul du nombre de sessions pour aujourd'hui -- */
        $beginToday  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d"), date("Y")));
        $endToday  = date("Y-m-d H:i:s", mktime(24, 59, 59, date("m")  , date("d"), date("Y")));
        $playersToday = count($repositorySession->createQueryBuilder('p')
                ->where('p.createDate BETWEEN :dayB AND :dayE')
                ->setParameter('dayB', $beginToday)
                ->setParameter('dayE', $endToday)
                ->getQuery()->getResult());

        /* -- Calcul du nombre de sessions pour hier -- */
        $beginYesterday  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
        $endYesterday = date("Y-m-d H:i:s", mktime(24, 59, 59, date("m")  , date("d")-1, date("Y")));
        $playersYesterday = count($repositorySession->createQueryBuilder('p')
            ->where('p.createDate BETWEEN :dayB AND :dayE')
            ->setParameter('dayB', $beginYesterday)
            ->setParameter('dayE', $endYesterday)
            ->getQuery()->getResult());

        return $this->render('CLICHESAdministrationBundle:Statistics:index.html.twig', array(
            'nbSessions' => $nbSessions,
            'nbSimpleSessions' => $nbSimpleSessions,
            'playersToday' => $playersToday,
            'playersYesterday' => $playersYesterday
        ));
    }

    public function statisticsByTeachingAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositorySession = $em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $sessionService = $this->container->get('cliches_player.playersessionaction');
        $repositoryTeachings = $em->getRepository('DATATeachingBundle:Teaching');
        $teachings = $repositoryTeachings->findAll();

        $arrayTeachings = array();
        foreach($teachings as $teaching) {
            $nbSessionTeaching = count($repositorySession->findByTeaching($teaching));
            $moyenneTempsTeaching = $sessionService->getMoyenneTemps(null, $teaching);
            $nbNonUserTeaching = $sessionService->getNumberNonUser($teaching);
            $averageSessionUserTeaching = $sessionService->getAverageUser($teaching);

            $arrayTeachings[] =
                ['name' => $teaching,
                    'sessions' => $nbSessionTeaching,
                    'moyenneTemps' => $moyenneTempsTeaching,
                    'nbNonUser' => $nbNonUserTeaching,
                    'averageSessionUser' => $averageSessionUserTeaching
                ];
        }

        return $this->render('CLICHESAdministrationBundle:Homepage:statisticsByTeaching.html.twig', array(
            'teachings' => $arrayTeachings
        ));
    }

    public function getStatistiquesInTimeAction()
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $repositorySession = $em->getRepository('CLICHESPlayerBundle:PlayerSession');

            $return = array();
            for($i = 0 ; $i <= 30 ; $i++)
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

    public function getStatistiquesNbClichesAction()
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $repositoryPlayerSession = $em->getRepository('CLICHESPlayerBundle:PlayerSession');
            $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
            $arrayTotal = array();

            foreach($repositoryPlayerSession->findAll() as $playerSession) {
                $playerOeuvres = $repositoryPlayerOeuvre->findByPlayerSession($playerSession);

                if(array_key_exists(count($playerOeuvres), $arrayTotal)) {
                    $arrayTotal[count($playerOeuvres)] = $arrayTotal[count($playerOeuvres)]+1;
                } else {
                    $arrayTotal[count($playerOeuvres)] = 1;
                }
            }
            ksort($arrayTotal);

            $arrayValue = array();
            $arrayNb = array();
            foreach($arrayTotal as $key => $nb) {
                $arrayNb[] = $key;
                $arrayValue[] = $nb;
            }

            return new Response(json_encode([$arrayNb, $arrayValue]));
        }
    }
}
