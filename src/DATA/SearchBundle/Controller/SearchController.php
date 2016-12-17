<?php

namespace DATA\SearchBundle\Controller;

use DATA\SearchBundle\Entity\SearchLog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DATA\SearchBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function indexAction()
    {
        return $this->render('DATASearchBundle:Search:index.html.twig');
    }

    public function searchAction($access=null, $teaching=null, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /* Définition des variables GET : */
        if($access == null) {
            if(isset($_GET['access']) AND ($_GET['access'] == 'true' OR $_GET['access'] == 'false' OR $_GET['access'] == true OR $_GET['access'] == false)) {
                $access = $_GET['access'];
            } elseif(isset($_POST['access']) AND ($_POST['access'] == 'true' OR $_POST['access'] == 'false' OR $_POST['access'] == true OR $_POST['access'] == false)) {
                $access = $_POST['access'];
            } else { $access = null; }
        }

        if($teaching == null) {
            if (isset($_GET['teaching'])) {
                $teaching = $_GET['teaching'];
            } elseif (isset($_POST['teaching'])) {
                $teaching = $_POST['teaching'];
            } else {$teaching = null;}
        }
        /* Définition des variables GET */

        $query = null;
        if(isset($_GET['query'])) {
            $query = $_GET['query'];
        } elseif(isset($_GET['id'])) {
            $searchLog = $em->getRepository('DATASearchBundle:SearchLog')->findOneById($_GET['id']);
            if ($searchLog === null) {throw $this->createNotFoundException('You need to specify a correct id parameter');}
            $query = $searchLog->getSearchValue();
        }

        /* Création du formulaire de recherche : */
        $form = $this->createForm(new SearchType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resultReturnedArray = array(
                'query' => $form->get('search')->getData(),
                'access' => $access,
                'teaching' => $teaching
            );
            if(isset($_GET['ppid']) AND !empty($_GET['ppid'])) { $resultReturnedArray['ppid'] = $_GET['ppid'];}
            elseif(isset($_POST['ppid']) AND !empty($_POST['ppid'])) { $resultReturnedArray['ppid'] = $_POST['ppid'];}

            return $this->redirect(
                $this->generateUrl('data_search_search_result', $resultReturnedArray)
            );
        }

        $viewRetunedArray = array(
            'form' => $form->createView(),
            'teaching' => $teaching,
            'access' => $access,
            'query' => $query
        );
        if(isset($_GET['ppid']) AND !empty($_GET['ppid'])) { $viewRetunedArray['ppid'] = $_GET['ppid'];}
        elseif(isset($_POST['ppid']) AND !empty($_POST['ppid'])) { $viewRetunedArray['ppid'] = $_POST['ppid'];}

        return $this->render('DATASearchBundle:Search:search.html.twig', $viewRetunedArray);
    }
    
    public function resultAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_GET['id'])) {
            $searchLog = $em->getRepository('DATASearchBundle:SearchLog')->findOneById($_GET['id']);
            if($searchLog === null) {throw $this->createNotFoundException('You need to specify a correct id parameter');}
            $query = $searchLog->getSearchValue();
        } elseif(!isset($_GET['query'])) {
            throw $this->createNotFoundException('You need to specify a query parameter');
        } else {
            $query = $_GET['query'];
        }


        if(isset($_GET['access']) AND ($_GET['access'] == 'true' OR $_GET['access'] == 'false')) {
            $access = $_GET['access'];
        } elseif(isset($_POST['access']) AND ($_POST['access'] == 'true' OR $_POST['access'] == 'false')) {
            $access = $_POST['access'];
        } else {
            $access = null;
        }

        if(isset($_GET['teaching'])) {
            $teaching = $_GET['teaching'];
        } elseif(isset($_POST['teaching'])) {
            $teaching = $_POST['teaching'];
        } else {
            $teaching = null;
        }

        $searchResults = $this->container->get('data_search.search')->search($query, $teaching);
        /*return $this->render('DATASearchBundle:Search:test.html.twig', array(
            'searchResults' => $searchResults
        ));*/

        /* -- Ajout de la recherche à la liste des logs -- */
        if(!isset($_GET['id'])) {
            $searchLog = new SearchLog();
            $searchLog->setSearchValue($query);
            $searchLog->setSearchNumberResults(count($searchResults));
            if ($this->getUser() != null) {
                $searchLog->setCreateUser($this->getUser());
            }
            $em->persist($searchLog);
            $em->flush();
        }

        $paginator = $this->get('knp_paginator');
        $results = $paginator->paginate(
            $searchResults,
            $request->query->get('page', 1)/*page number*/,
            100/*limit per page*/
        );

        $returnedArray = array(
            'searchResults' => $results,
            'teaching' => $teaching,
            'access' => $access,
            'query' => $query,
            'searchLog' => $searchLog
        );
        if(isset($_GET['ppid']) AND !empty($_GET['ppid'])) { $returnedArray['ppid'] = $_GET['ppid'];}
        elseif(isset($_POST['ppid']) AND !empty($_POST['ppid'])) { $returnedArray['ppid'] = $_POST['ppid'];}

        if($teaching == null) {
            return $this->render('DATASearchBundle:Search:results.html.twig', $returnedArray);
        } else {
            $returnedArray['teaching'] = $em->getRepository('DATATeachingBundle:Teaching')->findOneBySlug($teaching);
            $returnedArray['listEntities'] = $results;
            if($access == true or $access == 'true') {
                return $this->render('DATATeachingBundle:Teaching:view.html.twig', $returnedArray);
            } else {
                return $this->render('DATAPublicBundle:Teaching:view.html.twig', $returnedArray);
            }
        }
    }

    public function autocompleteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $index = $em->getRepository('DATASearchBundle:SearchIndex')->findAll();

        $return = array();

        foreach($index as $key => $item) {
            if (strpos($item->getValue(), $_GET['q']) !== false) {
                $return[] = $item->getValue();
            }
        }

        return new Response(json_encode($return));
    } 
}
