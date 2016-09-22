<?php

namespace CAS\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function aboutRedirectionAction()
    {
        return $this->redirect('http://about.karl-pineau.fr');
    }
}

