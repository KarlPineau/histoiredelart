<?php

namespace API\ClichesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultController extends Controller
{
    public function indexAction($proposal_id, $proposal_choice_value_id, Request $request)
    {
        $messages = [
            "messages" => [
                ["text" => "Oups... It seems there is an issue"],
                ["text" => "Try again later :)"]
            ]
        ];

        $em = $this->getDoctrine()->getManager();
        $playerProposal = $em->getRepository('CLICHESPlayerBundle:PlayerProposal')->findOneById($proposal_id);
        if ($playerProposal === null) {
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
            $response = new Response(json_encode($messages));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $playerProposalChoiceValue = $em->getRepository('CLICHESPlayerBundle:PlayerProposalChoiceValue')->findOneById($proposal_choice_value_id);
        if ($playerProposalChoiceValue === null) {
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
            $response = new Response(json_encode($messages));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $playerProposalChoiceValue->getPlayerProposalChoice()->setPlayerProposalChoiceValueSelected($playerProposalChoiceValue);

        $playerProposalChoiceValueTrue = $em->getRepository('CLICHESPlayerBundle:PlayerProposalChoiceValue')->findOneBy(array('isTrue' => true, 'playerProposalChoice' => $playerProposalChoiceValue->getPlayerProposalChoice()));
        if($playerProposalChoiceValue == $playerProposalChoiceValueTrue) {
            $playerProposalChoiceValue->getPlayerProposalChoice()->setCorrectAnswer(true);
            $messages = [
                "messages" => [
                    [
                        "text" => "Exact ! C'est une bonne réponse :)"
                    ],
                    [
                        "attachment" => [
                            "type" => "template",
                            "payload" => [
                                "template_type" => "button",
                                "text" => "Il s'agit bien de ".$playerProposalChoiceValue->getValue(),
                                "buttons" => [
                                    [
                                        "type" => "web_url",
                                        "url" => $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/data/item/'.$playerProposalChoiceValue->getEntity()->getId().'/facebook_messenger',
                                        "title" => "En savoir plus"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "text" => "Ou continuer",
                        "quick_replies" =>
                        [
                            [
                                "set_attributes" =>
                                [
                                    "cliches_session_id" => $playerProposalChoiceValue->getPlayerProposalChoice()->getPlayerProposal()->getPlayerOeuvre()->getPlayerSession()->getId(),
                                ],
                                "title" => "Continuer",
                                "block_names" => ["1-Cliches-Query"]
                            ],
                            [
                                "title" => "Nouvelle partie",
                                "block_names" => ["1-Cliches-Home"]
                            ]
                        ]
                    ]
                ]
            ];
        } else {
            $playerProposalChoiceValue->getPlayerProposalChoice()->setCorrectAnswer(false);
            $messages = [
                "messages" => [
                    [
                        "text" => "Mauvaise réponse :("
                    ],
                    [
                        "attachment" => [
                            "type" => "template",
                            "payload" => [
                                "template_type" => "button",
                                "text" => "Il s'agissait de ".$playerProposalChoiceValueTrue->getValue(),
                                "buttons" => [
                                    [
                                        "type" => "web_url",
                                        "url" => $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/data/item/'.$playerProposalChoiceValueTrue->getEntity()->getId().'/facebook_messenger',
                                        "title" => "En savoir plus"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "text" => "Ou continuer",
                        "quick_replies" =>
                            [
                                [
                                    "set_attributes" =>
                                        [
                                            "cliches_session_id" => $playerProposalChoiceValue->getPlayerProposalChoice()->getPlayerProposal()->getPlayerOeuvre()->getPlayerSession()->getId(),
                                        ],
                                    "title" => "Suivant",
                                    "block_names" => ["1-Cliches-Query"]
                                ],
                                [
                                    "title" => "Nouvelle partie",
                                    "block_names" => ["1-Cliches-Home"]
                                ]
                            ]
                    ]
                ]
            ];
        }
        $em->persist($playerProposalChoiceValue->getPlayerProposalChoice());
        $em->flush();

        $response = new Response(json_encode($messages));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
