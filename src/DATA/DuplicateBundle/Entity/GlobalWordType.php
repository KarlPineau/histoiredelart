<?php

namespace DATA\DuplicateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GlobalWordType
 *
 * @ORM\Entity(repositoryClass="DATA\DuplicateBundle\Entity\GlobalWordTypeRepository")
 */
class GlobalWordType
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
     * @ORM\ManyToMany(targetEntity="DATA\DuplicateBundle\Entity\WordType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wordTypes;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createDate", type="datetime", nullable=false)
     */
    protected $createDate;


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
        $this->wordTypes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return GlobalWordType
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
     * Add wordTypes
     *
     * @param \DATA\DuplicateBundle\Entity\WordType $wordTypes
     * @return GlobalWordType
     */
    public function addWordType(\DATA\DuplicateBundle\Entity\WordType $wordTypes)
    {
        $this->wordTypes[] = $wordTypes;

        return $this;
    }

    /**
     * Remove wordTypes
     *
     * @param \DATA\DuplicateBundle\Entity\WordType $wordTypes
     */
    public function removeWordType(\DATA\DuplicateBundle\Entity\WordType $wordTypes)
    {
        $this->wordTypes->removeElement($wordTypes);
    }

    /**
     * Get wordTypes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWordTypes()
    {
        return $this->wordTypes;
    }
}
