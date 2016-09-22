<?php

namespace CLICHES\PlayerBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerZoomView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PlayerZoomViewController extends Controller
{
    public function logZoomViewAction($playerOeuvre_id)
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest()) {
            
            $em = $this->getDoctrine()->getManager();
            $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
            $playerOeuvre = $repositoryPlayerOeuvre->findOneById($playerOeuvre_id);

            if($playerOeuvre != null) {
                $logZoom = new PlayerZoomView();
                $logZoom->setPlayerOeuvre($playerOeuvre);
                $em->persist($logZoom);
                $em->flush();

                return new Response(true);
            }
            return new Response(false);
        }
    }
}
