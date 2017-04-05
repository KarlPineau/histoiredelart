<?php

namespace DATA\DataBundle\Service;

use DATA\TeachingBundle\Service\teaching;
use Doctrine\ORM\EntityManager;
use DATA\DataBundle\Entity\Building;
use Symfony\Component\Security\Core\SecurityContext;

class wikidata
{
    protected $em;
    protected $security;
    protected $buzz;

    public function __construct(EntityManager $EntityManager, SecurityContext $security_context, $buzz)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
        $this->buzz = $buzz;
    }
    
    public function getQwd($url) {
        $qwd = null;

        if(preg_match('/https:\/\/www.wikidata.org\/entity\//', $url)) {
            $qwd = str_replace('https://www.wikidata.org/entity/', "", $url);
        } elseif(preg_match('/http:\/\/www.wikidata.org\/entity\//', $url)) {
            $qwd = str_replace('http://www.wikidata.org/entity/', "", $url);
        } elseif(preg_match('/https:\/\/www.wikidata.org\/wiki\//', $url)) {
            $qwd = str_replace('https://www.wikidata.org/wiki/', "", $url);
        } elseif(preg_match('/http:\/\/www.wikidata.org\/wiki\//', $url)) {
            $qwd = str_replace("http://www.wikidata.org/wiki/", "", $url);
        }
        
        return $qwd;
    }

    public function getEntity($qwd) {
        return json_decode($this->buzz->get('http://www.wikidata.org/w/api.php?action=wbgetentities&ids=' . urlencode($qwd) . '&format=json')->getContent())->entities->{$qwd};
    }
}
