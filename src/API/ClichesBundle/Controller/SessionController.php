<?php

namespace API\ClichesBundle\Controller;

use CLICHES\PlayerBundle\Entity\PlayerSession;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends Controller
{
    public function indexAction($user_id, $user_locale, $user_ref, $user_timezone, $teaching_name)
    {
        $messages = [
            "messages" => [
                ["text" => "Oups... It seems there is an issue"],
                ["text" => "Try again later :)"]
            ]
        ];

        $em = $this->getDoctrine()->getManager();

        switch ($teaching_name) {
            case 1:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Antiquités égyptiennes");
                break;
            case 2:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Archéologie du Proche-Orient");
                break;
            case 3:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Archéologie et art de l'Inde");
                break;
            case 8:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Archéologie & Arts de la Chine et du Japon");
                break;
            case 4:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Archéologie étrusque et romaine");
                break;
            case 5:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Archéologie grecque");
                break;
            case 6:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Archéologie nationale");
                break;
            case 7:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Archéologie paléochrétienne");
                break;
            case 9:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts du Moyen-Age");
                break;
            case 10:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts de la Chine et du Japon");
                break;
            case 11:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts de l'Inde et du monde indianisé");
                break;
            case 12:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts de Byzance");
                break;
            case 13:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts de l'Islam");
                break;
            case 14:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts de la Renaissance");
                break;
            case 15:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts du XVIIe siècle");
                break;
            case 16:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts du XVIIIe siècle");
                break;
            case 17:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts du XIXe siècle");
                break;
            case 18:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts du XXe siècle");
                break;
            case 19:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts et traditions populaires");
                break;
            case 20:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts précolombiens");
                break;
            case 21:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts de l'Afrique");
                break;
            case 22:
                $teaching = $em->getRepository('DATATeachingBundle:Teaching')->findOneByName("Arts de l'Océanie");
                break;
            default:
                $messages = [
                    "messages" => [
                        [
                            "text" => "Oups, je n'ai pas trouvé cette matière :(",
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

        $playerSession = new PlayerSession();
        $playerSession->setSimpleSession(false);
        $playerSession->setProposalType('modeChoice');
        $playerSession->setTeaching($teaching);

        $playerSession->setContext('facebook_messenger');

        $playerSession->setUserFacebookId($user_id);
        $playerSession->setUserFacebookLocal($user_locale);
        $playerSession->setUserFacebookRef($user_ref);
        $playerSession->setUserFacebookTimezone($user_timezone);

        $em->persist($playerSession);
        $em->flush();

        $messages = [
            "set_attributes" =>
            [
                "cliches_session_id" => $playerSession->getId(),
            ],
            "messages" => [
                ["text" => "C'est parti pour une session de clichés ! :)"],
            ]
        ];

        $response = new Response(json_encode($messages));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
