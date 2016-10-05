<?php

namespace CLICHES\PersonalPlaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PrivatePlayerView
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CLICHES\PersonalPlaceBundle\Entity\PrivatePlayerViewRepository")
 */
class PrivatePlayerView
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
     * @ORM\ManyToOne(targetEntity="CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer", inversedBy="privatePlayerViews")
     * @ORM\JoinColumn(nullable=true)
     */
    private $privatePlayer;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\ImageBundle\Entity\View")
     * @ORM\JoinColumn(nullable=true)
     */
    private $view;

    /**
     * @var string
     * @Assert\Ip
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
     * Set ipCreateUser
     *
     * @param string $ipCreateUser
     * @return PrivatePlayerView
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
     * @return PrivatePlayerView
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
     * @return PrivatePlayerView
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
     * Set privatePlayer
     *
     * @param \CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer $privatePlayer
     * @return PrivatePlayerView
     */
    public function setPrivatePlayer(\CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer $privatePlayer = null)
    {
        $this->privatePlayer = $privatePlayer;

        return $this;
    }

    /**
     * Get privatePlayer
     *
     * @return \CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer 
     */
    public function getPrivatePlayer()
    {
        return $this->privatePlayer;
    }

    /**
     * Set view
     *
     * @param \DATA\ImageBundle\Entity\View $view
     * @return PrivatePlayerView
     */
    public function setView(\DATA\ImageBundle\Entity\View $view = null)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view
     *
     * @return \DATA\ImageBundle\Entity\View 
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return PrivatePlayerView
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
     * @return PrivatePlayerView
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
}
