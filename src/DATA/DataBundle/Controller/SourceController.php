<?php

namespace DATA\DataBundle\Controller;

use DATA\DataBundle\Entity\SourceClick;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SourceController extends Controller
{
    public function addClickAction($source_id, $context)
    {
        $request = $this->get('request');

        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getEntityManager();
            
            $repositorySource = $em->getRepository('DATADataBundle:Source');
            $source = $repositorySource->findOneById($source_id);
            
            $sourceClick = new SourceClick();
            $sourceClick->setContext($context);
            $sourceClick->setIpCreateUser($this->container->get('request')->getClientIp());
            if($this->getUser() != null) {
                $sourceClick->setCreateUser($this->getUser());
            }
            $sourceClick->setSource($source);
            $em->persist($sourceClick);
            $em->flush();
            
            $response = new Response(json_encode("ok"));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }
}
