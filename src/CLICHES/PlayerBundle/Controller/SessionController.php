<?php

namespace CLICHES\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CLICHES\PlayerBundle\Entity\PlayerSession;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends Controller
{
    public function loadAction($teaching_id, $mode)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryTeaching = $em->getRepository('DATATeachingBundle:Teaching');
        $teaching = $repositoryTeaching->findOneById($teaching_id);
        if ($teaching === null) {throw $this->createNotFoundException('Enseignement : [id='.$teaching_id.'] inexistant.');}

        $session = new PlayerSession;
        if($this->getUser() != null) {$session->setCreateUser($this->getUser());}
        $session->setIpPlayerUser($this->container->get('request')->getClientIp());
        $session->setHTTPUSERAGENT($_SERVER['HTTP_USER_AGENT']);
        $session->setProposalType($mode);
        $session->setContext('webapp');
        if($mode == 'modeTest') {$session->setSimpleSession(true);} else {$session->setSimpleSession(false);}

        $session->setTeaching($teaching);
        $em->persist($session);
        $em->flush();

        return $this->redirect($this->generateUrl('cliches_player_selection_selection', array('playerSession_id' => $session->getId())));
    }

    public function forcedEndAction($session_id)
    {
        $request = $this->get('request');

        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getEntityManager();

            $repositorySource = $em->getRepository('CLICHESPlayerBundle:PlayerSession');
            $session = $repositorySource->findOneById($session_id);

            if ($session !== null AND empty($session->getDateEnd())) {
                $session->setDateEnd(new \DateTime());
                $em->persist($session);
                $em->flush();

                $response = new Response(json_encode("ok"));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }


        }
    }
}
