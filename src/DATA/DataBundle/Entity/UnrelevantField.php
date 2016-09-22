<?php

namespace DATA\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UnrelevantField
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class UnrelevantField
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
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=255, nullable=false)
     */
    private $field;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\DataBundle\Entity\Entity")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $entity;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\DataBundle\Entity\Building")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $building;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\DataBundle\Entity\Artwork")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $artwork;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmed", type="boolean", nullable=true, options="default: false;")
     */
    private $confirmed;

    /**
     * @ORM\ManyToOne(targetEntity="CAS\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $createUser;

    /**
     * @var string
     *
     * @ORM\Column(name="ipCreateUser", type="string", length=255, nullable=true)
     */
    protected $ipCreateUser;

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
     * Set field
     *
     * @param string $field
     * @return UnrelevantField
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return string 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return UnrelevantField
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
     * @return UnrelevantField
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
     * Set building
     *
     * @param \DATA\DataBundle\Entity\Building $building
     * @return UnrelevantField
     */
    public function setBuilding(\DATA\DataBundle\Entity\Building $building = null)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building
     *
     * @return \DATA\DataBundle\Entity\Building 
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set artwork
     *
     * @param \DATA\DataBundle\Entity\Artwork $artwork
     * @return UnrelevantField
     */
    public function setArtwork(\DATA\DataBundle\Entity\Artwork $artwork = null)
    {
        $this->artwork = $artwork;

        return $this;
    }

    /**
     * Get artwork
     *
     * @return \DATA\DataBundle\Entity\Artwork 
     */
    public function getArtwork()
    {
        return $this->artwork;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return UnrelevantField
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
     * @return UnrelevantField
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
     * Set confirmed
     *
     * @param boolean $confirmed
     * @return UnrelevantField
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean 
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set ipCreateUser
     *
     * @param string $ipCreateUser
     * @return UnrelevantField
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
     * Set entity
     *
     * @param \DATA\DataBundle\Entity\Entity $entity
     * @return UnrelevantField
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
