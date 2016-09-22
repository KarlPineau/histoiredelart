<?php

namespace CLICHES\PlayerBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class playerSessionAction 
{
    protected $em;
    protected $security;
    
    public function __construct(EntityManager $EntityManager, SecurityContext $security_context)
    {
        $this->em = $EntityManager;
        $this->security = $security_context;
    }
    
    public function countOeuvreByTeaching($teaching_name)
    {   
        $repositoryTeaching = $this->em->getRepository('DATATeachingBundle:Teaching');
        $teaching = $repositoryTeaching->findOneByName($teaching_name);
        if($teaching != NULL)
        {
            $number = count($teaching->getEntities());
        }
        else {$number = 0;}
        //$number = $repositoryTeaching->countOeuvres($teaching_name);
        
        return $number;
    }

    public function getAverageTime($teaching=null)
    {
        $repositorySession = $this->em->getRepository('CLICHESPlayerBundle:PlayerSession');

        if($teaching == null) {
            $sessions = $repositorySession->createQueryBuilder('p')
                        ->where('p.dateBegin IS NOT NULL AND p.dateEnd IS NOT NULL')
                        ->getQuery()->getResult();
        } elseif($teaching != null) {
            $sessions = $repositorySession->createQueryBuilder('p')
                     ->where('p.dateBegin IS NOT NULL AND p.dateEnd IS NOT NULL AND p.teaching = :teaching')
                     ->setParameter('teaching', $teaching)
                     ->getQuery()->getResult();
        }

        if(count($sessions) > 0) {
            $nbSecond = 0;
            foreach($sessions as $session) {
                if(!empty($session->getDateBegin())) {
                    $newInterval = $session->getDateBegin()->diff($session->getDateEnd());
                    $nbSecond += intval($newInterval->format('%S%a'));
                }
            }

            return strval(round(($nbSecond/count($sessions))/60, 2)).' min.';

        } else {
            return '0';
        }
    }

    public function getMedianTime($teaching=null)
    {
        $repositorySession = $this->em->getRepository('CLICHESPlayerBundle:PlayerSession');

        if($teaching == null) {
            $sessions = $repositorySession->createQueryBuilder('p')
                ->where('p.dateBegin IS NOT NULL AND p.dateEnd IS NOT NULL')
                ->getQuery()->getResult();
        } elseif($teaching != null) {
            $sessions = $repositorySession->createQueryBuilder('p')
                ->where('p.dateBegin IS NOT NULL AND p.dateEnd IS NOT NULL AND p.teaching = :teaching')
                ->setParameter('teaching', $teaching)
                ->getQuery()->getResult();
        }

        if(count($sessions) > 0) {
            $arraySessions = array();
            foreach($sessions as $session) {
                if(!empty($session->getDateBegin())) {
                    $newInterval = $session->getDateBegin()->diff($session->getDateEnd());
                    $arraySessions[] = intval($newInterval->format('%S%a'));
                }
            }
            asort($arraySessions);
            return strval(round(($arraySessions[(count($arraySessions)+1)/2])/60, 2)).' min.';
        } else {
            return '0';
        }
    }

    public function getDiffTimeSession($playerSession) {
        return date_diff($playerSession->getDateEnd(), $playerSession->getDateBegin());
    }

    public function getNumberNonUser($teaching=null)
    {
        $repositorySession = $this->em->getRepository('CLICHESPlayerBundle:PlayerSession');
        if($teaching == null) {
            return count($repositorySession->findByCreateUser(null));
        } elseif($teaching != null) {
            return count($repositorySession->findBy(array('createUser' => null, 'teaching' => $teaching)));
        }
    }

    /* Retourne la moyenne des (sessions ayant été joué par un user connecté)/nombre d'user */
    public function getAverageUser($teaching=null)
    {
        $repositoryUser = $this->em->getRepository('CASUserBundle:User');
        $repositorySession = $this->em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $nbCount = 0;
        $nbUsers = 0;

        foreach($repositoryUser->findAll() as $user) {
            if ($teaching == null) {
                $nbCount = $nbCount + count($repositorySession->findByCreateUser($user));

                if ($repositorySession->findByCreateUser($user) > 0) {
                    $nbUsers++;
                }
            } elseif ($teaching != null) {
                $nbCount = $nbCount + count($repositorySession->findBy(array('createUser' => $user, 'teaching' => $teaching)));

                if ($repositorySession->findBy(array('createUser' => $user, 'teaching' => $teaching)) > 0) {
                    $nbUsers++;
                }
            }
        }

        return $nbCount/$nbUsers;
    }

    /* Retourne la moyenne des (sessions ayant été joué par un user connecté)/nombre d'user */
    public function getMedianUser($teaching=null)
    {
        $repositoryUser = $this->em->getRepository('CASUserBundle:User');
        $repositorySession = $this->em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $arrayUserNumber = array();

        foreach($repositoryUser->findAll() as $user) {
            if($teaching == null) {
                $arrayUserNumber[] = count($repositorySession->findByCreateUser($user));
            } elseif($teaching != null) {
                $arrayUserNumber[] = count($repositorySession->findBy(array('createUser' => $user, 'teaching' => $teaching)));
            }
        }
        asort($arrayUserNumber);

        return $arrayUserNumber[(count($arrayUserNumber)+1)/2];
    }

    public function getNumberCliches($playerSession)
    {
        return count($this->em->getRepository('CLICHESPlayerBundle:PlayerOeuvre')->findBy(array('playerSession' => $playerSession)));
    }

    public function getAverageClichesSession()
    {
        set_time_limit(0);
        $repositoryOeuvre = $this->em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $repositorySession = $this->em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $nbSessions = count($repositorySession->findAll());
        $nbOeuvres = 0;

        foreach($repositorySession->findAll() as $session) {
            $nbOeuvres += count($repositoryOeuvre->findByPlayerSession($session));
        }

        return $nbOeuvres/$nbSessions;
    }

    public function getMedianClichesSession()
    {
        $repositoryOeuvre = $this->em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $repositorySession = $this->em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $arrayNumberOeuvres = array();

        foreach($repositorySession->findAll() as $session) {
            $arrayNumberOeuvres[] = count($repositoryOeuvre->findByPlayerSession($session));
        }
        asort($arrayNumberOeuvres);

        return $arrayNumberOeuvres[(count($arrayNumberOeuvres)+1)/2];
    }
}
