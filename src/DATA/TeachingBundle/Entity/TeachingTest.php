<?php

namespace DATA\TeachingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TeachingTest
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DATA\TeachingBundle\Entity\TeachingTestRepository")
 */
class TeachingTest
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
     * @ORM\ManyToOne(targetEntity="DATA\TeachingBundle\Entity\Teaching")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teaching;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\ImageBundle\Entity\View")
     * @ORM\JoinColumn(nullable=true)
     */
    private $view;

    /**
     * @ORM\OneToMany(targetEntity="DATA\TeachingBundle\Entity\TeachingTestVote", mappedBy="teachingTest")
     * @ORM\JoinColumn(nullable=true)
     */
    private $votes;

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
     * @var string
     *
     * @ORM\Column(name="updateComment", type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le commentaire ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    protected $updateComment;


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
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return TeachingTest
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
     * @return TeachingTest
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
     * Set updateComment
     *
     * @param string $updateComment
     * @return TeachingTest
     */
    public function setUpdateComment($updateComment)
    {
        $this->updateComment = $updateComment;

        return $this;
    }

    /**
     * Get updateComment
     *
     * @return string 
     */
    public function getUpdateComment()
    {
        return $this->updateComment;
    }

    /**
     * Set teaching
     *
     * @param \DATA\TeachingBundle\Entity\Teaching $teaching
     * @return TeachingTest
     */
    public function setTeaching(\DATA\TeachingBundle\Entity\Teaching $teaching)
    {
        $this->teaching = $teaching;

        return $this;
    }

    /**
     * Get teaching
     *
     * @return \DATA\TeachingBundle\Entity\Teaching 
     */
    public function getTeaching()
    {
        return $this->teaching;
    }

    /**
     * Set view
     *
     * @param \DATA\ImageBundle\Entity\View $view
     * @return TeachingTest
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
     * Add votes
     *
     * @param \DATA\TeachingBundle\Entity\TeachingTestVote $votes
     * @return TeachingTest
     */
    public function addVote(\DATA\TeachingBundle\Entity\TeachingTestVote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \DATA\TeachingBundle\Entity\TeachingTestVote $votes
     */
    public function removeVote(\DATA\TeachingBundle\Entity\TeachingTestVote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return TeachingTest
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
     * @return TeachingTest
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
