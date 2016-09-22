<?php

namespace DATA\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntityViews
 *
 * @ORM\Entity()
 */
class EntityViews
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
     * @ORM\ManyToMany(targetEntity="DATA\ImageBundle\Entity\View")
     * @ORM\JoinColumn(nullable=true)
     */
    private $views;


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
        $this->views = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add views
     *
     * @param \DATA\ImageBundle\Entity\View $views
     * @return EntityViews
     */
    public function addView(\DATA\ImageBundle\Entity\View $views)
    {
        $this->views[] = $views;

        return $this;
    }

    /**
     * Remove views
     *
     * @param \DATA\ImageBundle\Entity\View $views
     */
    public function removeView(\DATA\ImageBundle\Entity\View $views)
    {
        $this->views->removeElement($views);
    }

    /**
     * Get views
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getViews()
    {
        return $this->views;
    }
}
