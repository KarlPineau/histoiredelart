<?php

namespace DATA\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportingController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryReporting = $em->getRepository('DATAPublicBundle:Reporting');

        $reportings = $repositoryReporting->findBy(array('traitement' => false));

        return $this->render('DATAAdministrationBundle:Reporting:index.html.twig', array(
            'reportings' => $reportings,
        ));
    }

    public function validateAction($reporting_id)
    {
        $em = $this->getDoctrine()->getManager();
        $reporting = $em->getRepository('DATAPublicBundle:Reporting')->findOneById($reporting_id);

        if ($reporting === null) {
            throw $this->createNotFoundException('Reporting : [id='.$reporting_id.'] inexistant.');
        }

        $reportingAction = $this->container->get('data_public.reporting');
        $reportingAction->validateReporting($reporting);

        if($reporting->getCreateUser() != null and $em->getRepository('CASUserBundle:UserPreferences')->findOneByUser($reporting->getCreateUser())->getDataReportingConfirmation() == true) {
            /* -- l'utilisateur souhaite être notifié par mail du résultat : -- */
            $message = \Swift_Message::newInstance()
                ->setSubject('Merci d\'avoir contribué à DATA !')
                ->setFrom('cliches@histoiredelart.fr')
                ->setTo($reporting->getCreateUser()->getEmail())
                ->setBody(
                    $this->renderView(
                        'DATAAdministrationBundle:Reporting:mailInfoYes.html.twig',
                        array('reporting' => $reporting)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
        }

        $this->get('session')->getFlashBag()->add('notice', 'Reporting a bien été notifié comme validé.' );
        return $this->forward('DATAAdministrationBundle:Reporting:index');
    }


    public function refuseAction($reporting_id)
    {
        $em = $this->getDoctrine()->getManager();
        $reporting = $em->getRepository('DATAPublicBundle:Reporting')->findOneById($reporting_id);

        if ($reporting === null) {
            throw $this->createNotFoundException('Reporting : [id='.$reporting_id.'] inexistant.');
        }

        $reportingAction = $this->container->get('data_public.reporting');
        $reportingAction->refuseReporting($reporting);

        if($reporting->getCreateUser() != null and $em->getRepository('CASUserBundle:UserPreferences')->findOneByUser($reporting->getCreateUser())->getDataReportingConfirmation() == true) {
            /* -- l'utilisateur souhaite être notifié par mail du résultat : -- */
            $message = \Swift_Message::newInstance()
                ->setSubject('Merci d\'avoir contribué à DATA !')
                ->setFrom('cliches@histoiredelart.fr')
                ->setTo($reporting->getCreateUser()->getEmail())
                ->setBody(
                    $this->renderView(
                        'DATAAdministrationBundle:Reporting:mailInfoNo.html.twig',
                        array('reporting' => $reporting)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
        }

        $this->get('session')->getFlashBag()->add('notice', 'Reporting a bien été notifié comme refusé.' );
        return $this->forward('DATAAdministrationBundle:Reporting:index');
    }
}
