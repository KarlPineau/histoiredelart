<?php

namespace DATA\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntitySources
 *
 * @ORM\Entity()
 */
class EntitySources
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="DATA\DataBundle\Entity\Source", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $sources;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sources = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sources
     *
     * @param \DATA\DataBundle\Entity\Source $sources
     * @return EntitySources
     */
    public function addSource(\DATA\DataBundle\Entity\Source $sources)
    {
        $this->sources[] = $sources;

        return $this;
    }

    /**
     * Remove sources
     *
     * @param \DATA\DataBundle\Entity\Source $sources
     */
    public function removeSource(\DATA\DataBundle\Entity\Source $sources)
    {
        $this->sources->removeElement($sources);
    }

    /**
     * Get sources
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSources()
    {
        return $this->sources;
    }
}
