<?php

namespace DATA\ImageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DATA\ImageBundle\Entity\ImageRepository")
 */
class Image
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
     * @Assert\NotBlank(
     *     message = "Le champ 'Copyright' ne peut pas être vide"
     * )
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le champ 'Copyright' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="copyright", type="string", length=255, nullable=false)
     */
    private $copyright;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\ImageBundle\Entity\View", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $view;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\ImageBundle\Entity\FileImage", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $fileImage;

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
     * Set copyright
     *
     * @param string $copyright
     * @return Image
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * Get copyright
     *
     * @return string 
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Image
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
     * @return Image
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
     * Set view
     *
     * @param \DATA\ImageBundle\Entity\View $view
     * @return Image
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
     * Set fileImage
     *
     * @param \DATA\ImageBundle\Entity\FileImage $fileImage
     * @return Image
     */
    public function setFileImage(\DATA\ImageBundle\Entity\FileImage $fileImage = null)
    {
        $this->fileImage = $fileImage;

        return $this;
    }

    /**
     * Get fileImage
     *
     * @return \DATA\ImageBundle\Entity\FileImage 
     */
    public function getFileImage()
    {
        return $this->fileImage;
    }

    /**
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return Image
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
     * @return Image
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
