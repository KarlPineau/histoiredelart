<?php

namespace CAS\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestMailController extends Controller
{
    public function indexAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('karl.pineau@ens-lyon.fr')
            ->setTo('karl.pineau@gmail.com')
            ->setBody('You should see me from the profiler!')
        ;

        $this->get('mailer')->send($message);
        return $this->render('CASHomeBundle:Home:index.html.twig');
    }
}
