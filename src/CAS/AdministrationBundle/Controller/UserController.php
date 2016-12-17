<?php

namespace CAS\AdministrationBundle\Controller;

use CAS\UserBundle\Entity\UserPreferences;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function indexAction(Request $request)
    {
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('CASUserBundle:User');
        $users = $repositoryUser->findAll();

        $paginator  = $this->get('knp_paginator');
        $listUsers = $paginator->paginate(
            $users,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );

        return $this->render('CASAdministrationBundle:User:index.html.twig', array(
            'users' => $listUsers
        ));
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUser = $em->getRepository('CASUserBundle:User');
        $repositoryVisit = $em->getRepository('DATAPublicBundle:Visit');
        $repositoryImportSession = $em->getRepository('DATAImportBundle:EntityImportSession');
        $repositoryDataReporting = $em->getRepository('DATAPublicBundle:Reporting');
        $repositoryPlayerSession = $em->getRepository('CLICHESPlayerBundle:PlayerSession');
        $user = $repositoryUser->findOneById($id);

        if ($user === null) { throw $this->createNotFoundException('User : [id='.$id.'] inexistant.'); }

        $lastVisitsEntity = $repositoryVisit->findBy(array('createUser' => $user), array('createDate' => 'DESC'), 10);
        $lastEntityImportSessions = $repositoryImportSession->findBy(array('createUser' => $user), array('createDate' => 'DESC'), 10);
        $lastDataReportings = $repositoryDataReporting->findBy(array('createUser' => $user), array('createDate' => 'DESC'), 10);
        $lastPlayerSessions = $repositoryPlayerSession->findBy(array('createUser' => $user), array('createDate' => 'DESC'), 10);

        return $this->render('CASAdministrationBundle:User:view.html.twig', array(
            'user' => $user,
            'lastVisitsEntity' => $lastVisitsEntity,
            'lastEntityImportSessions' => $lastEntityImportSessions,
            'lastDataReportings' => $lastDataReportings,
            'lastPlayerSessions' => $lastPlayerSessions
        ));
    }

    public function removeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('CASUserBundle:User')->findOneById($id);
        if($user === null) { throw $this->createNotFoundException('User : [id='.$id.'] inexistant.'); }

        $this->get('cas_user.remove')->remove($user);
        $em->flush();
        $this->get('session')->getFlashBag()->add('notice', 'L\'utilisateur a bien été supprimé');
        return $this->redirect($this->generateUrl('cas_administration_user_index'));
    }
}
