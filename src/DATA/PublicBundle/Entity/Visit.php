<?php

namespace DATA\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Visit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DATA\PublicBundle\Entity\VisitRepository")
 */
class Visit
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
    protected $entity;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\TeachingBundle\Entity\Teaching")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $teaching;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\TeachingBundle\Entity\University")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $university;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=255, nullable=true)
     */
    protected $context;

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
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Visit
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
     * @return Visit
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
     * Set teaching
     *
     * @param \DATA\TeachingBundle\Entity\Teaching $teaching
     * @return Visit
     */
    public function setTeaching(\DATA\TeachingBundle\Entity\Teaching $teaching = null)
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
     * Set university
     *
     * @param \DATA\TeachingBundle\Entity\University $university
     * @return Visit
     */
    public function setUniversity(\DATA\TeachingBundle\Entity\University $university = null)
    {
        $this->university = $university;

        return $this;
    }

    /**
     * Get university
     *
     * @return \DATA\TeachingBundle\Entity\University 
     */
    public function getUniversity()
    {
        return $this->university;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return Visit
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
     * Set entity
     *
     * @param \DATA\DataBundle\Entity\Entity $entity
     * @return Visit
     */
    public function setEntity(\DATA\DataBundle\Entity\Entity $entity = null)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return \DATA\DataBundle\Entity\Entity 
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return Visit
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string 
     */
    public function getContext()
    {
        return $this->context;
    }
}
