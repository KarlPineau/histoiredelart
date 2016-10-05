<?php

namespace TB\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TB\ModelBundle\Entity\TestedItemResult;
use TB\ModelBundle\Entity\TestedSession;

class PlayerController extends Controller
{
    public function indexAction($testedGame_id)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if ($testedGame === null) {throw $this->createNotFoundException('TestedGame : [id='.$testedGame_id.'] inexistant.');}

        $testedSession = new TestedSession();
        $testedSession->setIpCreateUser($this->container->get('request')->getClientIp());
        if($this->getUser() != null) {$testedSession->setCreateUser($this->getUser());}
        $testedSession->setIsRandomized(false);
        $testedSession->setTestedGame($testedGame);
        $em->persist($testedSession);
        $em->flush();

        return $this->render('TBPlayerBundle:Player:index.html.twig', array(
            'testedGame' => $testedGame,
            'testedSession' => $testedSession
        ));
    }

    public function trackingAction($answers)
    {
        $request = $this->get('request');
        if($request->isXmlHttpRequest())
        {
            $answers = json_decode($answers);
            $em = $this->getDoctrine()->getManager();

            foreach($answers as $answer) {
                $testedItem = $em->getRepository('TBModelBundle:TestedItem')->findOneById($answer->testedItem_id);
                $testedSession = $em->getRepository('TBModelBundle:TestedSession')->findOneById($answer->testedSession_id);

                $testedItemResult = new TestedItemResult();
                $testedItemResult->setProposedLabel($answer->label);
                $testedItemResult->setTestedItem($testedItem);
                $testedItemResult->setTestedSession($testedSession);
                if($this->getUser() != null) {$testedItemResult->setCreateUser($this->getUser());}
                $em->persist($testedItemResult);
            }
            $em->flush();

            return new Response(json_encode(true));
        }
    }

    public function trackingToSmallWindowAction($testedSession_id)
    {
        $request = $this->get('request');
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $testedSession = $em->getRepository('TBModelBundle:TestedSession')->findOneById($testedSession_id);
            $testedSession->setToSmallWindow(true);
            $em->persist($testedSession);
            $em->flush();

            return new Response(json_encode(true));
        }
    }
}
