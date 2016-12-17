<?php

namespace HOME\HomeBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapAllContentsSubscriber implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param ObjectManager         $manager
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, ObjectManager $manager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->manager = $manager;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'registerAllContentsPages',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function registerAllContentsPages(SitemapPopulateEvent $event)
    {
        $items = $this->manager->getRepository('DATADataBundle:Entity')->findBy(array('importValidation' => true));
        foreach ($items as $item) {
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'data_public_entity_view',
                        ['id' => $item->getId()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_MONTHLY,
                    0.7
                ),
                'default'
            );
        }

        $teachings = $this->manager->getRepository('DATATeachingBundle:Teaching')->findBy(array('onLine' => true));
        foreach ($teachings as $teaching) {
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'data_public_teaching_view',
                        ['slug' => $teaching->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_MONTHLY,
                    0.7
                ),
                'default'
            );
        }

        $universities = $this->manager->getRepository('DATATeachingBundle:University')->findAll();
        foreach ($universities as $university) {
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'data_public_university_view',
                        ['slug' => $university->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_MONTHLY,
                    0.7
                ),
                'default'
            );
        }

        $testedGames = $this->manager->getRepository('TBModelBundle:TestedGame')->findBy(array('isOnline' => true, 'isPrivate' => false));
        foreach ($testedGames as $testedGame) {
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'tb_player_player_index',
                        ['testedGame_id' => $testedGame->getId()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_WEEKLY,
                    0.7
                ),
                'default'
            );
        }
    }
}
