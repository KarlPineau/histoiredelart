<?php

namespace DATA\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EntityImportSession
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DATA\ImportBundle\Entity\EntityImportSessionRepository")
 */
class EntityImportSession
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
     * @var \stdClass
     *
     * @ORM\OneToMany(targetEntity="DATA\DataBundle\Entity\Entity", mappedBy="importSession", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $entities;

    /**
     * @var string
     *
     * @ORM\Column(name="importValidation", type="boolean", nullable=false)
     */
    private $importValidation;

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
     * Constructor
     */
    public function __construct()
    {
        $this->entities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return EntityImportSession
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
     * @return EntityImportSession
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
     * Add entities
     *
     * @param \DATA\DataBundle\Entity\Entity $entities
     * @return EntityImportSession
     */
    public function addEntity(\DATA\DataBundle\Entity\Entity $entities)
    {
        $this->entities[] = $entities;

        return $this;
    }

    /**
     * Remove entities
     *
     * @param \DATA\DataBundle\Entity\Entity $entities
     */
    public function removeEntity(\DATA\DataBundle\Entity\Entity $entities)
    {
        $this->entities->removeElement($entities);
    }

    /**
     * Get entities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return EntityImportSession
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
     * @return EntityImportSession
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
     * Set importValidation
     *
     * @param boolean $importValidation
     * @return EntityImportSession
     */
    public function setImportValidation($importValidation)
    {
        $this->importValidation = $importValidation;

        return $this;
    }

    /**
     * Get importValidation
     *
     * @return boolean 
     */
    public function getImportValidation()
    {
        return $this->importValidation;
    }
}
