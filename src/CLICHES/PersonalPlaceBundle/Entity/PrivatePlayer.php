<?php

namespace CLICHES\PersonalPlaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PrivatePlayer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CLICHES\PersonalPlaceBundle\Entity\PrivatePlayerRepository")
 */
class PrivatePlayer
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
     * @ORM\ManyToOne(targetEntity="DATA\PersonalPlaceBundle\Entity\UserSessions", inversedBy="privatePlayers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userSession;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="countPlayer", type="integer")
     */
    private $countPlayer;

    /**
     * @var string
     *
     * @ORM\Column(name="simpleSession", type="boolean", nullable=true, options="default: false;")
     */
    private $simpleSession;

    /**
     * @ORM\OneToMany(targetEntity="CLICHES\PersonalPlaceBundle\Entity\PrivatePlayerView", mappedBy="privatePlayer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $privatePlayerViews;

    /**
     * @var string
     *
     * @ORM\Column(name="ipCreateUser", type="string", length=255, nullable=true)
     */
    protected $ipCreateUser;

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
     * @return PrivatePlayer
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
     * Set countPlayer
     *
     * @param integer $countPlayer
     * @return PrivatePlayer
     */
    public function setCountPlayer($countPlayer)
    {
        $this->countPlayer = $countPlayer;

        return $this;
    }

    /**
     * Get countPlayer
     *
     * @return integer 
     */
    public function getCountPlayer()
    {
        return $this->countPlayer;
    }

    /**
     * Set simpleSession
     *
     * @param boolean $simpleSession
     * @return PrivatePlayer
     */
    public function setSimpleSession($simpleSession)
    {
        $this->simpleSession = $simpleSession;

        return $this;
    }

    /**
     * Get simpleSession
     *
     * @return boolean 
     */
    public function getSimpleSession()
    {
        return $this->simpleSession;
    }

    /**
     * Set ipCreateUser
     *
     * @param string $ipCreateUser
     * @return PrivatePlayer
     */
    public function setIpCreateUser($ipCreateUser)
    {
        $this->ipCreateUser = $ipCreateUser;

        return $this;
    }

    /**
     * Get ipCreateUser
     *
     * @return string 
     */
    public function getIpCreateUser()
    {
        return $this->ipCreateUser;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return PrivatePlayer
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
     * @return PrivatePlayer
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
     * @return PrivatePlayer
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
     * @return PrivatePlayer
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
     * Set userSession
     *
     * @param \DATA\PersonalPlaceBundle\Entity\UserSessions $userSession
     * @return PrivatePlayer
     */
    public function setUserSession(\DATA\PersonalPlaceBundle\Entity\UserSessions $userSession)
    {
        $this->userSession = $userSession;

        return $this;
    }

    /**
     * Get userSession
     *
     * @return \DATA\PersonalPlaceBundle\Entity\UserSessions
     */
    public function getUserSession()
    {
        return $this->userSession;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->privatePlayerViews = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add privatePlayerViews
     *
     * @param \CLICHES\PersonalPlaceBundle\Entity\PrivatePlayerView $privatePlayerViews
     * @return PrivatePlayer
     */
    public function addPrivatePlayerView(\CLICHES\PersonalPlaceBundle\Entity\PrivatePlayerView $privatePlayerViews)
    {
        $this->privatePlayerViews[] = $privatePlayerViews;

        return $this;
    }

    /**
     * Remove privatePlayerViews
     *
     * @param \CLICHES\PersonalPlaceBundle\Entity\PrivatePlayerView $privatePlayerViews
     */
    public function removePrivatePlayerView(\CLICHES\PersonalPlaceBundle\Entity\PrivatePlayerView $privatePlayerViews)
    {
        $this->privatePlayerViews->removeElement($privatePlayerViews);
    }

    /**
     * Get privatePlayerViews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrivatePlayerViews()
    {
        return $this->privatePlayerViews;
    }
}
