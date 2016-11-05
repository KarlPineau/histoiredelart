<?php

namespace TB\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TestedItemResult
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TB\ModelBundle\Entity\TestedItemResultRepository")
 */
class TestedItemResult
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
     * @ORM\ManyToOne(targetEntity="TB\ModelBundle\Entity\TestedItemResultSession", inversedBy="testedItemResultSession")
     * @ORM\JoinColumn(nullable=false)
     */
    private $testedItemResultSession;

    /**
     * @ORM\ManyToOne(targetEntity="TB\ModelBundle\Entity\TestedItem")
     * @ORM\JoinColumn(nullable=false)
     */
    private $testedItem;

    /**
     * @var string
     *
     * @ORM\Column(name="proposedLabel", type="string", length=255, nullable=true)
     */
    private $proposedLabel;

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
     * Set proposedLabel
     *
     * @param string $proposedLabel
     * @return TestedItemResult
     */
    public function setProposedLabel($proposedLabel)
    {
        $this->proposedLabel = $proposedLabel;

        return $this;
    }

    /**
     * Get proposedLabel
     *
     * @return string 
     */
    public function getProposedLabel()
    {
        return $this->proposedLabel;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return TestedItemResult
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
     * @return TestedItemResult
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
     * Set testedItem
     *
     * @param \TB\ModelBundle\Entity\TestedItem $testedItem
     * @return TestedItemResult
     */
    public function setTestedItem(\TB\ModelBundle\Entity\TestedItem $testedItem)
    {
        $this->testedItem = $testedItem;

        return $this;
    }

    /**
     * Get testedItem
     *
     * @return \TB\ModelBundle\Entity\TestedItem 
     */
    public function getTestedItem()
    {
        return $this->testedItem;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return TestedItemResult
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
     * @return TestedItemResult
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
     * Set testedItemResultSession
     *
     * @param \TB\ModelBundle\Entity\TestedItemResultSession $testedItemResultSession
     * @return TestedItemResult
     */
    public function setTestedItemResultSession(\TB\ModelBundle\Entity\TestedItemResultSession $testedItemResultSession)
    {
        $this->testedItemResultSession = $testedItemResultSession;

        return $this;
    }

    /**
     * Get testedItemResultSession
     *
     * @return \TB\ModelBundle\Entity\TestedItemResultSession 
     */
    public function getTestedItemResultSession()
    {
        return $this->testedItemResultSession;
    }
}
