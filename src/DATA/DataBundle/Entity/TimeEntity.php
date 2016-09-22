<?php

namespace DATA\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TimeEntity
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class TimeEntity
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
     * @ORM\Column(name="absoluteBeginDate", type="string", length=255, nullable=true)
     */
    private $absoluteBeginDate;

    /**
     * @var string
     *
     * @ORM\Column(name="absoluteBeginDateYear", type="string", length=4, nullable=true)
     */
    private $absoluteBeginDateYear;

    /**
     * @var string
     *
     * @ORM\Column(name="absoluteBeginDatePrefix", type="string", length=255, nullable=true)
     */
    private $absoluteBeginDatePrefix;

    /**
     * @var string
     *
     * @ORM\Column(name="absoluteEndDate", type="string", length=255, nullable=true)
     */
    private $absoluteEndDate;

    /**
     * @var string
     *
     * @ORM\Column(name="absoluteEndDateYear", type="string", length=4, nullable=true)
     */
    private $absoluteEndDateYear;

    /**
     * @var string
     *
     * @ORM\Column(name="absoluteEndDatePrefix", type="string", length=255, nullable=true)
     */
    private $absoluteEndDatePrefix;

    /**
     * @var boolean
     *
     * @ORM\Column(name="absoluteBeginDateBC", type="boolean", nullable=true, options={"default":false})
     */
    private $absoluteBeginDateBC;

    /**
     * @var boolean
     *
     * @ORM\Column(name="absoluteEndDateBC", type="boolean", nullable=true, options={"default":false})
     */
    private $absoluteEndDateBC;

    /**
     * @var string
     *
     * @ORM\Column(name="relativeDate", type="string", length=255, nullable=true)
     */
    private $relativeDate;

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
     * Set absoluteBeginDate
     *
     * @param string $absoluteBeginDate
     * @return TimeEntity
     */
    public function setAbsoluteBeginDate($absoluteBeginDate)
    {
        $this->absoluteBeginDate = $absoluteBeginDate;

        return $this;
    }

    /**
     * Get absoluteBeginDate
     *
     * @return string 
     */
    public function getAbsoluteBeginDate()
    {
        return $this->absoluteBeginDate;
    }

    /**
     * Set absoluteBeginDateYear
     *
     * @param string $absoluteBeginDateYear
     * @return TimeEntity
     */
    public function setAbsoluteBeginDateYear($absoluteBeginDateYear)
    {
        $this->absoluteBeginDateYear = $absoluteBeginDateYear;

        return $this;
    }

    /**
     * Get absoluteBeginDateYear
     *
     * @return string 
     */
    public function getAbsoluteBeginDateYear()
    {
        return $this->absoluteBeginDateYear;
    }

    /**
     * Set absoluteBeginDatePrefix
     *
     * @param string $absoluteBeginDatePrefix
     * @return TimeEntity
     */
    public function setAbsoluteBeginDatePrefix($absoluteBeginDatePrefix)
    {
        $this->absoluteBeginDatePrefix = $absoluteBeginDatePrefix;

        return $this;
    }

    /**
     * Get absoluteBeginDatePrefix
     *
     * @return string 
     */
    public function getAbsoluteBeginDatePrefix()
    {
        return $this->absoluteBeginDatePrefix;
    }

    /**
     * Set absoluteEndDate
     *
     * @param string $absoluteEndDate
     * @return TimeEntity
     */
    public function setAbsoluteEndDate($absoluteEndDate)
    {
        $this->absoluteEndDate = $absoluteEndDate;

        return $this;
    }

    /**
     * Get absoluteEndDate
     *
     * @return string 
     */
    public function getAbsoluteEndDate()
    {
        return $this->absoluteEndDate;
    }

    /**
     * Set absoluteEndDateYear
     *
     * @param string $absoluteEndDateYear
     * @return TimeEntity
     */
    public function setAbsoluteEndDateYear($absoluteEndDateYear)
    {
        $this->absoluteEndDateYear = $absoluteEndDateYear;

        return $this;
    }

    /**
     * Get absoluteEndDateYear
     *
     * @return string 
     */
    public function getAbsoluteEndDateYear()
    {
        return $this->absoluteEndDateYear;
    }

    /**
     * Set absoluteEndDatePrefix
     *
     * @param string $absoluteEndDatePrefix
     * @return TimeEntity
     */
    public function setAbsoluteEndDatePrefix($absoluteEndDatePrefix)
    {
        $this->absoluteEndDatePrefix = $absoluteEndDatePrefix;

        return $this;
    }

    /**
     * Get absoluteEndDatePrefix
     *
     * @return string 
     */
    public function getAbsoluteEndDatePrefix()
    {
        return $this->absoluteEndDatePrefix;
    }

    /**
     * Set absoluteBeginDateBC
     *
     * @param boolean $absoluteBeginDateBC
     * @return TimeEntity
     */
    public function setAbsoluteBeginDateBC($absoluteBeginDateBC)
    {
        $this->absoluteBeginDateBC = $absoluteBeginDateBC;

        return $this;
    }

    /**
     * Get absoluteBeginDateBC
     *
     * @return boolean 
     */
    public function getAbsoluteBeginDateBC()
    {
        return $this->absoluteBeginDateBC;
    }

    /**
     * Set absoluteEndDateBC
     *
     * @param boolean $absoluteEndDateBC
     * @return TimeEntity
     */
    public function setAbsoluteEndDateBC($absoluteEndDateBC)
    {
        $this->absoluteEndDateBC = $absoluteEndDateBC;

        return $this;
    }

    /**
     * Get absoluteEndDateBC
     *
     * @return boolean 
     */
    public function getAbsoluteEndDateBC()
    {
        return $this->absoluteEndDateBC;
    }

    /**
     * Set relativeDate
     *
     * @param string $relativeDate
     * @return TimeEntity
     */
    public function setRelativeDate($relativeDate)
    {
        $this->relativeDate = $relativeDate;

        return $this;
    }

    /**
     * Get relativeDate
     *
     * @return string 
     */
    public function getRelativeDate()
    {
        return $this->relativeDate;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return TimeEntity
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
     * @return TimeEntity
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
     * @return TimeEntity
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
     * @return TimeEntity
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
