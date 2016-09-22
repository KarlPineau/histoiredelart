<?php

namespace DATA\PublicBundle\Controller;

use DATA\PublicBundle\Entity\Visit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UniversityController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('DATATeachingBundle:University');
        $universities = $repository->findAll();

        return $this->render('DATAPublicBundle:University:index.html.twig', array(
            'universities' => $universities
        ));
    }

    public function viewAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('DATATeachingBundle:University');
        $university = $repository->findOneBySlug($slug);

        if ($university === null) { throw $this->createNotFoundException('Université : [slug='.$slug.'] inexistante.'); }

        $visit = new Visit();
        $visit->setUniversity($university);
        $visit->setCreateUser($this->getUser());
        $em->persist($visit);
        $em->flush();

        $teachings = $this->get('data_teaching.university')->getTeachings($university, 'restrict');

        return $this->render('DATAPublicBundle:University:view.html.twig', array(
            'university' => $university,
            'teachings' => $teachings));
    }
}
