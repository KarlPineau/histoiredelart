<?php

namespace API\ClichesBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerOeuvre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SelectionController extends Controller
{
    public function indexAction($session_id)
    {
        $messages = [
            "messages" => [
                ["text" => "Oups... It seems there is an issue"],
                ["text" => "Try again later :)"]
            ]
        ];

        $em = $this->getDoctrine()->getManager();
        $session = $em->getRepository('CLICHESPlayerBundle:PlayerSession')->findOneById($session_id);
        if($session === null) {
            $messages = [
                "messages" => [
                    ["text" => "Oups... Je détecte une petite erreur :)"],
                    [
                        "text" => "Peux-tu relancer la partie ?",
                        "quick_replies" => [
                            [
                                "title" => "Relancer",
                                "block_names" => ["1-Cliches-Home"]
                            ]
                        ]
                    ]
                ]
            ];
        }

        $passedPlayerOeuvres = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre')->findByPlayerSession($session);
        $array_result = $this->get('cliches_player.playerselectionaction')->getRandViewForTeaching($session->getTeaching(), $passedPlayerOeuvres);

        if($array_result[0] == 'no_enough_entities' OR $array_result[0] == 'no_entities') {
            $messages = [
                "messages" => [
                    [
                        "text" => "Oups... Il n'y a plus assez d'oeuvres pour continuer :(",
                        "quick_replies" => [
                            [
                                "title" => "Nouvelle partie",
                                "block_names" => ["1-Cliches-Home"]
                            ]
                        ]
                    ]
                ]
            ];
        } elseif($array_result[0] == 'view') {
            $playerOeuvre = new PlayerOeuvre();
            $playerOeuvre->setPlayerSession($session);
            $playerOeuvre->setView($array_result[1]);
            $em->persist($playerOeuvre);

            $session->setDateEnd(new \DateTime());
            $em->persist($session);

            $em->flush();

            return $this->redirect($this->generateUrl('api_cliches_proposal_choice_index', array('playerOeuvre_id' => $playerOeuvre->getId(), 'authToken' => "Kp4r5jIN3A")));
        }

        $response = new Response(json_encode($messages));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
