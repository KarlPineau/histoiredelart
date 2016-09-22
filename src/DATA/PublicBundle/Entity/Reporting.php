<?php

namespace DATA\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reporting
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DATA\PublicBundle\Entity\ReportingRepository")
 */
class Reporting
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
     * @var string
     *
     * @ORM\Column(name="reporting", type="string", length=255)
     */
    private $reporting;

    /**
     * @var string
     *
     * @ORM\Column(name="traitement", type="boolean", nullable=true, options="default: false;")
     */
    private $traitement;

    /**
     * @var string
     *
     * @ORM\Column(name="validate", type="boolean", nullable=true, options="default: false;")
     */
    private $validate;

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
     * Set reporting
     *
     * @param string $reporting
     * @return Reporting
     */
    public function setReporting($reporting)
    {
        $this->reporting = $reporting;

        return $this;
    }

    /**
     * Get reporting
     *
     * @return string 
     */
    public function getReporting()
    {
        return $this->reporting;
    }

    /**
     * Set traitement
     *
     * @param boolean $traitement
     * @return Reporting
     */
    public function setTraitement($traitement)
    {
        $this->traitement = $traitement;

        return $this;
    }

    /**
     * Get traitement
     *
     * @return boolean 
     */
    public function getTraitement()
    {
        return $this->traitement;
    }

    /**
     * Set validate
     *
     * @param boolean $validate
     * @return Reporting
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * Get validate
     *
     * @return boolean 
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Reporting
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
     * @return Reporting
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
     * @return Reporting
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
     * @return Reporting
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
}
