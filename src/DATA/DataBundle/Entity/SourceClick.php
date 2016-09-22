<?php

namespace DATA\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SourceClick
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class SourceClick
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
     * @ORM\ManyToOne(targetEntity="DATA\DataBundle\Entity\Source")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $source;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=255)
     */
    private $context;

    /**
     * @ORM\ManyToOne(targetEntity="CAS\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $createUser;

    /**
     * @var string
     * @Assert\Ip
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
     * Set context
     *
     * @param string $context
     * @return SourceClick
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

    /**
     * Set ipCreateUser
     *
     * @param string $ipCreateUser
     * @return SourceClick
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
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return SourceClick
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
     * @return SourceClick
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
     * Set source
     *
     * @param \DATA\DataBundle\Entity\Source $source
     * @return SourceClick
     */
    public function setSource(\DATA\DataBundle\Entity\Source $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \DATA\DataBundle\Entity\Source 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return SourceClick
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
}
