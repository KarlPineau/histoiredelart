<?php
namespace DATA\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SitemapsController extends Controller
{

    public function sitemapAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $urls = array();
        $hostname = $this->getRequest()->getHost();

        $urls[] = array('loc' => $this->get('router')->generate('data_public_home_index_home'), 'priority' => '1.0');
        
        foreach ($this->get('data_data.entity')->find('all', null, 'restrict') as $entity) {
            $urls[] = array('loc' => $this->get('router')->generate('data_public_entity_view',
                array('id' => $entity->getId())), 'priority' => '0.6');
        }

        foreach ($em->getRepository('DATATeachingBundle:Teaching')->findAll() as $teaching) {
            $urls[] = array('loc' => $this->get('router')->generate('data_public_teaching_view',
                array('slug' => $teaching->getSlug())), 'priority' => '0.8');
        }

        foreach ($em->getRepository('DATATeachingBundle:University')->findAll() as $university) {
            $urls[] = array('loc' => $this->get('router')->generate('data_public_university_view',
                array('slug' => $university->getSlug())), 'priority' => '0.8');
        }

        return $this->render('DATAPublicBundle:Sitemaps:sitemap.xml.twig', array('urls' => $urls, 'hostname' => $hostname));
    }
}