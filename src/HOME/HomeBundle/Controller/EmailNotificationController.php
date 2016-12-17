<?php

namespace HOME\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmailNotificationController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        /*
         * Liste des entités à récupérer :
         * - Liste des sessions CLICHES du jour
         * - Liste des sessions ConfusArt du jour
         * - Liste des imports du jour dans data
         * - Liste des recherches du jour dans data
         * - Liste des Suggestions du jour (data + clichés)
         * - Liste des parties ConfusArt créées ce jour
         * */

        $CLICHESPlayerSessions = $em->getRepository('CLICHESPlayerBundle:PlayerSession')->findBy(array('createDate'));

        foreach($em->getRepository('CASUserBundle:User')->findByRole('ROLE_ADMIN') as $user) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Au revoir !')
                ->setFrom('cliches@histoiredelart.fr')
                ->setTo($user->getEmail())
                ->setBody($this->renderView('HOMEHomeBundle:EmailNotification:mail.html.twig'), 'text/html');
            $this->get('mailer')->send($message);
        }

        return $this->render('HOMEHomeBundle:Home:index.html.twig');
    }
}
