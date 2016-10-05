<?php

namespace DATA\PersonalPlaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserSessions
 *
 * @ORM\Entity(repositoryClass="DATA\PersonalPlaceBundle\Entity\UserSessionsRepository")
 */
class UserSessions
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
     * @ORM\OneToMany(targetEntity="CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer", mappedBy="userSession")
     * @ORM\JoinColumn(nullable=false)
     */
    private $privatePlayers;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\ImageBundle\Entity\View")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $view;

    /**
     * @ORM\ManyToOne(targetEntity="CAS\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
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
     * Constructor
     */
    public function __construct()
    {
        $this->privatePlayers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return UserSessions
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
     * @return UserSessions
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
     * Add privatePlayers
     *
     * @param \CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer $privatePlayers
     * @return UserSessions
     */
    public function addPrivatePlayer(\CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer $privatePlayers)
    {
        $this->privatePlayers[] = $privatePlayers;

        return $this;
    }

    /**
     * Remove privatePlayers
     *
     * @param \CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer $privatePlayers
     */
    public function removePrivatePlayer(\CLICHES\PersonalPlaceBundle\Entity\PrivatePlayer $privatePlayers)
    {
        $this->privatePlayers->removeElement($privatePlayers);
    }

    /**
     * Get privatePlayers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrivatePlayers()
    {
        return $this->privatePlayers;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return UserSessions
     */
    public function setCreateUser(\CAS\UserBundle\Entity\User $createUser)
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
     * Set view
     *
     * @param \DATA\ImageBundle\Entity\View $view
     * @return UserSessions
     */
    public function setView(\DATA\ImageBundle\Entity\View $view)
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
}
