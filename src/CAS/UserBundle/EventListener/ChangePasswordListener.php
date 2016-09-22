<?php
namespace CAS\UserBundle\EventListener;

use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;

class ChangePasswordListener implements EventSubscriberInterface
{
    protected $em;

    public function __construct(EntityManager $EntityManager)
    {
        $this->em = $EntityManager;
    }

    public static function getSubscribedEvents()
    {
        return [ FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onPasswordChangingSuccess' ];
    }

    public function onPasswordChangingSuccess( UserEvent $event )
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Votre mot de passe a été modifié - Clichés!')
            ->setFrom('cliches@histoiredelart.fr')
            ->setTo($event->getUser()->getEmail())
            ->setBody($this->renderView('CASUserBundle:ChangePassword:mail.html.twig', array('email' => $event->getUser()->getEmail())),'text/html')
        ;
        $this->get('mailer')->send($message);
    }
}