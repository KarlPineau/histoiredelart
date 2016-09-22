<?php

namespace DATA\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Building
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DATA\DataBundle\Repository\BuildingRepository")
 */
class Building
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le champ 'Nom' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    public $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(prefix="edifice-", fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
     */
    public $slug;
    
    /**
     * @var string
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le champ 'Auteur' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="auteur", type="string", length=255, nullable=true)
     */
    public $auteur;
    
    /**
     * @var string
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le champ 'Commanditaire' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="commanditaire", type="string", length=255, nullable=true)
     */
    public $commanditaire;
    
    /**
     * @var string
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le champ 'Datation' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="datation", type="string", length=255, nullable=true)
     */
    public $datation;
    
    /**
     * @var string
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le champ 'Matières et Techniques' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="mattech", type="string", length=255, nullable=true)
     */
    public $mattech;
    
    /**
     * @var string
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le champ 'Dimensions' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="dimensions", type="string", length=255, nullable=true)
     */
    public $dimensions;
    
    /**
     * @var string
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le champ 'style' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="style", type="string", length=255, nullable=true)
     */
    public $style;

    /**
    * @ORM\ManyToOne(targetEntity="CAS\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    public $createUser;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createDate", type="datetime", nullable=false)
     */
    public $createDate;

    /**
    * @ORM\ManyToOne(targetEntity="CAS\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    public $updateUser;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updateDate", type="datetime", nullable=true)
     */
    public $updateDate;


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
     * Set
     *
     * @return Building
     */
    public function set($field, $value)
    {
        $this->{$field} = $value;

        return $this;
    }

    /**
     * Get
     */
    public function get($field)
    {
        return $this->{$field};
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Building
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Building
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return Building
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set commanditaire
     *
     * @param string $commanditaire
     * @return Building
     */
    public function setCommanditaire($commanditaire)
    {
        $this->commanditaire = $commanditaire;

        return $this;
    }

    /**
     * Get commanditaire
     *
     * @return string 
     */
    public function getCommanditaire()
    {
        return $this->commanditaire;
    }

    /**
     * Set datation
     *
     * @param string $datation
     * @return Building
     */
    public function setDatation($datation)
    {
        $this->datation = $datation;

        return $this;
    }

    /**
     * Get datation
     *
     * @return string 
     */
    public function getDatation()
    {
        return $this->datation;
    }

    /**
     * Set mattech
     *
     * @param string $mattech
     * @return Building
     */
    public function setMattech($mattech)
    {
        $this->mattech = $mattech;

        return $this;
    }

    /**
     * Get mattech
     *
     * @return string 
     */
    public function getMattech()
    {
        return $this->mattech;
    }

    /**
     * Set dimensions
     *
     * @param string $dimensions
     * @return Building
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * Get dimensions
     *
     * @return string 
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Set style
     *
     * @param string $style
     * @return Building
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return string 
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Building
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
     * @return Building
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
     * @return Building
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
     * @return Building
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
