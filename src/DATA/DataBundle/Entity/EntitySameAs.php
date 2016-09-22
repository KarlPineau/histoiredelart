<?php

namespace DATA\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntitySameAs
 *
 * @ORM\Entity()
 */
class EntitySameAs
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
     * @ORM\ManyToMany(targetEntity="DATA\DataBundle\Entity\SameAs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $sameAs;


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
        $this->sameAs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sameAs
     *
     * @param \DATA\DataBundle\Entity\SameAs $sameAs
     * @return EntitySameAs
     */
    public function addSameA(\DATA\DataBundle\Entity\SameAs $sameAs)
    {
        $this->sameAs[] = $sameAs;

        return $this;
    }

    /**
     * Remove sameAs
     *
     * @param \DATA\DataBundle\Entity\SameAs $sameAs
     */
    public function removeSameA(\DATA\DataBundle\Entity\SameAs $sameAs)
    {
        $this->sameAs->removeElement($sameAs);
    }

    /**
     * Get sameAs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSameAs()
    {
        return $this->sameAs;
    }
}
