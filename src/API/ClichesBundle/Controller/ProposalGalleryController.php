<?php

namespace API\ClichesBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerProposal;
use CLICHES\PlayerBundle\Entity\PlayerProposalChoice;
use CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProposalGalleryController extends Controller
{
    public function indexAction($playerOeuvre_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $playerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre')->findOneById($playerOeuvre_id);
        if($playerOeuvre === null) {
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

        $playerProposal = new PlayerProposal();
        $playerProposal->setPlayerOeuvre($playerOeuvre);
        $em->persist($playerProposal);

        $image = $em->getRepository('DATAImageBundle:Image')->findOneByView($playerOeuvre->getView());

        $entity = $this->get('data_data.entity')->getByView($playerOeuvre->getView());

        $listFields = $this->get('data_data.entity')->getListFieldsForEntity($entity);
        shuffle($listFields);
        foreach($listFields as $key => $fieldContainer) {
            if($this->get('data_data.entity')->valueByField($fieldContainer['field'], $entity) != 'empty' AND
                $this->get('data_data.entity')->valueByField($fieldContainer['field'], $entity) != 'unrelevant' AND
                $this->get('data_data.entity')->valueByField($fieldContainer['field'], $entity) != 'noproperty') {

                $playerProposalChoice = new PlayerProposalChoice();
                $playerProposalChoice->setPlayerProposal($playerProposal);
                $playerProposalChoice->setField($fieldContainer['field']);
                $em->persist($playerProposalChoice);

                $choicesArray = array();
                $choices = $this->get('cliches_player.playerproposalchoiceaction')->getValuesAction($playerOeuvre, $fieldContainer['field'], 3);
                foreach($choices as $choice) {
                    $playerProposalChoiceValue = new PlayerProposalChoiceValue();
                    $playerProposalChoiceValue->setEntity($choice['entity']);
                    $playerProposalChoiceValue->setField($fieldContainer['field']);
                    $playerProposalChoiceValue->setValue($choice['value']);
                    $playerProposalChoiceValue->setIsTrue($choice['quizz']);
                    $playerProposalChoiceValue->setPlayerProposalChoice($playerProposalChoice);
                    $em->persist($playerProposalChoiceValue);
                    $em->flush();

                    $choicesArray[] = ['value' => $choice['value'], 'id' => $playerProposalChoiceValue->getId()];
                }

                break;
            }
        }

        return $this->generateMessengerFormat($playerProposal, $playerOeuvre->getPlayerSession()->getTeaching(), $this->get('data_data.entity')->getByView($playerOeuvre->getView()), $playerOeuvre->getView(), $fieldContainer, $choicesArray, $request);
    }

    public function generateMessengerFormat($playerProposal, $teaching, $entity, $view, $propertyContainer, $values, Request $request)
    {
        $image = $this->get('data_image.image')->getOneByView($view);

        $valuesReply = array();
        foreach($values as $value) {
            $valuesReply[] =
                [
                    "set_attributes" =>
                    [
                        "proposal_id" => $playerProposal->getId(),
                        "proposal_choice_value_id" =>  $value['id'],
                    ],
                    "title" => $value['value'],
                    "block_names" => ["1-Cliches-Result"]
                ];
        }
        shuffle($valuesReply);

        $messages = [
            "messages" => [
                ["text" => "Voici une question en rapport avec ".$teaching->getName()],
                [
                    "attachment" => [
                        "type" => "image",
                        "payload" =>  [
                            "url" => $request->getScheme().'://'.$request->getHttpHost().$request->getBasePath().'/uploads/gallery/'.$image->getFileImage()->getImageName()
                        ]
                    ]
                ],
                [
                    "text" => $propertyContainer['label'].' ?',
                    "quick_replies" => $valuesReply
                ],
            ]
        ];


        $response = new Response(json_encode($messages));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
