<?php
namespace CAS\UserBundle\EventListener;

use CAS\UserBundle\Entity\Log;
use CAS\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LoginListener implements EventSubscriberInterface
{
    protected $em;
    protected $securityContext;

    public function __construct(EntityManager $EntityManager, $securityContext)
    {
        $this->em = $EntityManager;
        $this->securityContext = $securityContext;
    }

    public static function getSubscribedEvents()
    {
        return [ SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin' ];
    }

    public function onSecurityInteractiveLogin( InteractiveLoginEvent $event )
    {
        $token = $this->securityContext->getToken();
        if( is_object( $token ) ) {
            $user = $token->getUser();
            if ($user instanceof User) {
                $log = new Log();
                $log->setUser($user);
                $log->setContext('normal');
                $this->em->persist($log);
                $this->em->flush();
            }
        }
    }
}