<?php

namespace TB\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TB\ModelBundle\Entity\TestedItemResult;
use TB\ModelBundle\Entity\TestedItemResultSession;
use TB\ModelBundle\Entity\TestedSession;

class PlayerController extends Controller
{
    public function indexAction($testedGame_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $testedGame = $em->getRepository('TBModelBundle:TestedGame')->findOneById($testedGame_id);
        if ($testedGame === null or ($testedGame->getIsOnline() == false AND ($this->getUser() != $testedGame->getCreateUser() OR !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')))) {throw $this->createNotFoundException('TestedGame : [id='.$testedGame_id.'] inexistant.');}

        $testedSession = new TestedSession();
        $testedSession->setIpCreateUser($this->container->get('request')->getClientIp());
        if($this->getUser() != null) {$testedSession->setCreateUser($this->getUser());}
        $testedSession->setIsRandomized(false);
        $testedSession->setTestedGame($testedGame);
        $em->persist($testedSession);
        $em->flush();

        $urlToPlay = $this->generateUrl('tb_player_player_index', array('testedGame_id' => $testedGame->getId()), true);
        $createdBy = '';
        if($testedGame->getIsOfficial() == true) {
            $createdBy = 'Clichés!';
        } else {
            $createdBy = $testedGame->getCreateUser()->getUsername();
        }
        $image = $testedGame->getIcon();
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->addMeta('name', 'author', $createdBy)
            ->addMeta('property', 'og:description', 'Teste tes connaissances en histoire de l\'art sur Clichés! - Viens participer au jeu conçu par '.$createdBy.' et défis tes amis!')
            ->addMeta('property', 'og:image', $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . $this->get('templating.helper.assets')->getUrl('uploads/gallery/'.$image->getFileImage()->getImageName()))
            ->addMeta('property', 'og:url', $urlToPlay)
            ->addMeta('property', 'og:title', $testedGame->getTitle().' - Clichés!')
            ->addMeta('property', 'twitter:title', $testedGame->getTitle().' - Clichés!')
            ->addMeta('property', 'twitter:description', 'Teste tes connaissances en histoire de l\'art sur Clichés! - Viens participer au jeu conçu par '.$createdBy.' et défis tes amis!')
            ->addMeta('property', 'twitter:image', $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . $this->get('templating.helper.assets')->getUrl('uploads/gallery/'.$image->getFileImage()->getImageName()))
            ->addMeta('property', 'twitter:url', $urlToPlay)
            ->addMeta('property', 'twitter:card', 'summary_large_image')
        ;

        return $this->render('TBPlayerBundle:Player:index.html.twig', array(
            'testedGame' => $testedGame,
            'testedSession' => $testedSession
        ));
    }

    public function trackingAction($answers)
    {
        $request = $this->get('request');
        if($request->isXmlHttpRequest())
        {
            $answers = json_decode($answers);
            $em = $this->getDoctrine()->getManager();

            $testedItemResultSession = new TestedItemResultSession();
            foreach($answers as $key => $answer) {
                $testedItem = $em->getRepository('TBModelBundle:TestedItem')->findOneById($answer->testedItem_id);
                $testedSession = $em->getRepository('TBModelBundle:TestedSession')->findOneById($answer->testedSession_id);
                if($key == 0) {$testedItemResultSession->setTestedSession($testedSession);}

                $testedItemResult = new TestedItemResult();
                $testedItemResult->setProposedLabel($answer->label);
                $testedItemResult->setTestedItem($testedItem);
                $testedItemResult->setTestedItemResultSession($testedItemResultSession);
                if($this->getUser() != null) {$testedItemResult->setCreateUser($this->getUser());}
                $em->persist($testedItemResult);
            }
            $em->persist($testedItemResultSession);
            $em->flush();

            return new Response(json_encode(true));
        }
    }

    public function trackingTooSmallWindowAction($testedSession_id)
    {
        $request = $this->get('request');
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $testedSession = $em->getRepository('TBModelBundle:TestedSession')->findOneById($testedSession_id);
            $testedSession->setTooSmallWindow(true);
            $em->persist($testedSession);
            $em->flush();

            return new Response(json_encode(true));
        }
    }
}
