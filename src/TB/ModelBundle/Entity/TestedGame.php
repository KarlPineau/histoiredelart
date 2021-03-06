<?php

namespace TB\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TestedGame
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TB\ModelBundle\Entity\TestedGameRepository")
 */
class TestedGame
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
     * @ORM\OneToMany(targetEntity="TB\ModelBundle\Entity\TestedItem", mappedBy="testedGame", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $testedItems;


    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\ImageBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $icon;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isPrivate", type="boolean")
     */
    private $isPrivate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isOfficial", type="boolean")
     */
    private $isOfficial;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isRandomized", type="boolean")
     */
    private $isRandomized;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isOnline", type="boolean")
     */
    private $isOnline;

    /**
     * @ORM\ManyToOne(targetEntity="CAS\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $createUser;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createDate", type="datetime", nullable=false)
     */
    protected $createDate;

    /**
     * @ORM\ManyToOne(targetEntity="CAS\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $updateUser;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updateDate", type="datetime", nullable=true)
     */
    protected $updateDate;


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
     * Set title
     *
     * @param string $title
     * @return TestedGame
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set isRandomized
     *
     * @param boolean $isRandomized
     * @return TestedGame
     */
    public function setIsRandomized($isRandomized)
    {
        $this->isRandomized = $isRandomized;

        return $this;
    }

    /**
     * Get isRandomized
     *
     * @return boolean 
     */
    public function getIsRandomized()
    {
        return $this->isRandomized;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return TestedGame
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return TestedGame
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return TestedGame
     */
    public function setCreateUser(\CAS\UserBundle\Entity\User $createUser = null)
    {
        $this->createUser = $createUser;

        return $this;
    }

    /**
     * Get createUser
     *
     * @return \CAS\UserBundle\Entity\User
     */
    public function getCreateUser()
    {
        return $this->createUser;
    }

    /**
     * Set updateUser
     *
     * @param \CAS\UserBundle\Entity\User $updateUser
     * @return TestedGame
     */
    public function setUpdateUser(\CAS\UserBundle\Entity\User $updateUser = null)
    {
        $this->updateUser = $updateUser;

        return $this;
    }

    /**
     * Get updateUser
     *
     * @return \CAS\UserBundle\Entity\User
     */
    public function getUpdateUser()
    {
        return $this->updateUser;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->testedItems = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add testedItems
     *
     * @param \TB\ModelBundle\Entity\TestedItem $testedItems
     * @return TestedGame
     */
    public function addTestedItem(\TB\ModelBundle\Entity\TestedItem $testedItems)
    {
        $this->testedItems[] = $testedItems;

        return $this;
    }

    /**
     * Remove testedItems
     *
     * @param \TB\ModelBundle\Entity\TestedItem $testedItems
     */
    public function removeTestedItem(\TB\ModelBundle\Entity\TestedItem $testedItems)
    {
        $this->testedItems->removeElement($testedItems);
    }

    /**
     * Get testedItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTestedItems()
    {
        return $this->testedItems;
    }

    /**
     * Set isOnline
     *
     * @param boolean $isOnline
     * @return TestedGame
     */
    public function setIsOnline($isOnline)
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    /**
     * Get isOnline
     *
     * @return boolean 
     */
    public function getIsOnline()
    {
        return $this->isOnline;
    }

    /**
     * Set icon
     *
     * @param \DATA\ImageBundle\Entity\Image $icon
     * @return TestedGame
     */
    public function setIcon(\DATA\ImageBundle\Entity\Image $icon = null)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return \DATA\ImageBundle\Entity\Image 
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set isOfficial
     *
     * @param boolean $isOfficial
     * @return TestedGame
     */
    public function setIsOfficial($isOfficial)
    {
        $this->isOfficial = $isOfficial;

        return $this;
    }

    /**
     * Get isOfficial
     *
     * @return boolean 
     */
    public function getIsOfficial()
    {
        return $this->isOfficial;
    }

    /**
     * Set isPrivate
     *
     * @param boolean $isPrivate
     * @return TestedGame
     */
    public function setIsPrivate($isPrivate)
    {
        $this->isPrivate = $isPrivate;

        return $this;
    }

    /**
     * Get isPrivate
     *
     * @return boolean 
     */
    public function getIsPrivate()
    {
        return $this->isPrivate;
    }
}
