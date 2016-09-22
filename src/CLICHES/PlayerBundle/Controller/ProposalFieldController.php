<?php

namespace CLICHES\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProposalFieldController extends Controller
{
    public function proposalFieldAction($idImg)
    {
        $request = $this->get('request');
        if($request->isXmlHttpRequest())
        {
            return new Response($this->get('cliches_player.playerproposalfieldaction')->foundFields($idImg));
        }
            
    }
}
