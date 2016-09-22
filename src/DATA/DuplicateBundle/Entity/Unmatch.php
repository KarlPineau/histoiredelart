<?php

namespace DATA\DuplicateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Unmatch
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DATA\DuplicateBundle\Entity\UnmatchRepository")
 */
class Unmatch
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
     * @ORM\ManyToOne(targetEntity="DATA\DataBundle\Entity\Entity")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $entityLeft;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\DataBundle\Entity\Entity")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $entityRight;

    /**
     * @var boolean
     *
     * @ORM\Column(name="unmatch", type="boolean", nullable=true)
     */
    private $unmatch;

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
     * Set unmatch
     *
     * @param boolean $unmatch
     * @return Unmatch
     */
    public function setUnmatch($unmatch)
    {
        $this->unmatch = $unmatch;

        return $this;
    }

    /**
     * Get unmatch
     *
     * @return boolean 
     */
    public function getUnmatch()
    {
        return $this->unmatch;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Unmatch
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
     * @return Unmatch
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
     * @return Unmatch
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
     * @return Unmatch
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
     * Set entityLeft
     *
     * @param \DATA\DataBundle\Entity\Entity $entityLeft
     * @return Unmatch
     */
    public function setEntityLeft(\DATA\DataBundle\Entity\Entity $entityLeft = null)
    {
        $this->entityLeft = $entityLeft;

        return $this;
    }

    /**
     * Get entityLeft
     *
     * @return \DATA\DataBundle\Entity\Entity 
     */
    public function getEntityLeft()
    {
        return $this->entityLeft;
    }

    /**
     * Set entityRight
     *
     * @param \DATA\DataBundle\Entity\Entity $entityRight
     * @return Unmatch
     */
    public function setEntityRight(\DATA\DataBundle\Entity\Entity $entityRight = null)
    {
        $this->entityRight = $entityRight;

        return $this;
    }

    /**
     * Get entityRight
     *
     * @return \DATA\DataBundle\Entity\Entity 
     */
    public function getEntityRight()
    {
        return $this->entityRight;
    }
}
