<?php

namespace TB\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TestedItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TB\ModelBundle\Entity\TestedItemRepository")
 */
class TestedItem
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
     * @ORM\ManyToOne(targetEntity="TB\ModelBundle\Entity\TestedGame", inversedBy="testedItem")
     * @ORM\JoinColumn(nullable=false)
     */
    private $testedGame;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\DataBundle\Entity\Entity")
     * @ORM\JoinColumn(nullable=true)
     */
    private $dataEntity;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\ImageBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $dataImage;

    /**
     * @var string
     *
     * @ORM\Column(name="itemLabel", type="string", length=255, nullable=false)
     */
    private $itemLabel;

    /**
     * @var integer
     *
     * @ORM\Column(name="itemOrder", type="integer", nullable=false)
     */
    private $itemOrder;

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
     * Set itemLabel
     *
     * @param string $itemLabel
     * @return TestedItem
     */
    public function setItemLabel($itemLabel)
    {
        $this->itemLabel = $itemLabel;

        return $this;
    }

    /**
     * Get itemLabel
     *
     * @return string 
     */
    public function getItemLabel()
    {
        return $this->itemLabel;
    }

    /**
     * Set itemOrder
     *
     * @param integer $itemOrder
     * @return TestedItem
     */
    public function setItemOrder($itemOrder)
    {
        $this->itemOrder = $itemOrder;

        return $this;
    }

    /**
     * Get itemOrder
     *
     * @return integer 
     */
    public function getItemOrder()
    {
        return $this->itemOrder;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return TestedItem
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
     * @return TestedItem
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
     * Set testedGame
     *
     * @param \TB\ModelBundle\Entity\TestedGame $testedGame
     * @return TestedItem
     */
    public function setTestedGame(\TB\ModelBundle\Entity\TestedGame $testedGame)
    {
        $this->testedGame = $testedGame;

        return $this;
    }

    /**
     * Get testedGame
     *
     * @return \TB\ModelBundle\Entity\TestedGame 
     */
    public function getTestedGame()
    {
        return $this->testedGame;
    }

    /**
     * Set dataEntity
     *
     * @param \DATA\DataBundle\Entity\Entity $dataEntity
     * @return TestedItem
     */
    public function setDataEntity(\DATA\DataBundle\Entity\Entity $dataEntity = null)
    {
        $this->dataEntity = $dataEntity;

        return $this;
    }

    /**
     * Get dataEntity
     *
     * @return \DATA\DataBundle\Entity\Entity 
     */
    public function getDataEntity()
    {
        return $this->dataEntity;
    }

    /**
     * Set dataImage
     *
     * @param \DATA\ImageBundle\Entity\Image $dataImage
     * @return TestedItem
     */
    public function setDataImage(\DATA\ImageBundle\Entity\Image $dataImage = null)
    {
        $this->dataImage = $dataImage;

        return $this;
    }

    /**
     * Get dataImage
     *
     * @return \DATA\ImageBundle\Entity\Image
     */
    public function getDataImage()
    {
        return $this->dataImage;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return TestedItem
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
     * @return TestedItem
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
