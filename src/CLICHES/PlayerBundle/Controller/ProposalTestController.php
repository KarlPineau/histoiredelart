<?php

namespace CLICHES\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProposalTestController extends Controller
{
    public function proposalTestAction($playerOeuvre_id)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryPlayerOeuvre = $em->getRepository('CLICHESPlayerBundle:PlayerOeuvre');
        $playerOeuvre = $repositoryPlayerOeuvre->findOneById($playerOeuvre_id);
        if ($playerOeuvre === null) {throw $this->createNotFoundException('Session : [id='.$playerOeuvre_id.'] inexistante.');}

        $repositoryImage = $em->getRepository('DATAImageBundle:Image');
        $image = $repositoryImage->findOneByView($playerOeuvre->getView());

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->addMeta('property', 'og:title', 'Clichés!')
            ->addMeta('property', 'og:description', 'Aidez-moi à reconnaitre ce cliché !')
            ->addMeta('property', 'og:image', $this->get('templating.helper.assets')->getUrl('uploads/gallery/'.$image->getFileImage()->getImageName()))
        ;

        return $this->render('CLICHESPlayerBundle:Proposal:visualisation.html.twig', array(
            'playerSession' => $playerOeuvre->getPlayerSession(),
            'image' => $image,
            'view' => $playerOeuvre->getView()
        ));
    }
}
