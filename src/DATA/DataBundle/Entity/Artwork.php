<?php

namespace DATA\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Artwork
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DATA\DataBundle\Repository\ArtworkRepository")
 */
class Artwork
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
     *      maxMessage = "Le champ 'Sujet' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="sujet", type="string", length=255, nullable=false)
     */
    public $sujet;

    /**
     * @var string
     *
     * @Gedmo\Slug(prefix="oeuvre-", fields={"sujet"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
     */
    public $slug;

    /**
     * @var string
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le champ 'Sujet Iconographique' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="sujet_icono", type="string", length=255, nullable=true)
     */
    public $sujetIcono;

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
     *      maxMessage = "Le champ 'Provenance' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="provenance", type="string", length=255, nullable=true)
     */
    public $provenance;

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
     *      maxMessage = "Le champ 'Style' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="style", type="string", length=255, nullable=true)
     */
    public $style;

    /**
     * @var string
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Le champ 'Lieu de Conservation' ne peut pas dépasser {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="lieu_de_conservation", type="string", length=255, nullable=true)
     */
    public $lieuDeConservation;
    
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
     * Set
     *
     * @return Artwork
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sujet
     *
     * @param string $sujet
     * @return Artwork
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return string 
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set sujetIcono
     *
     * @param string $sujetIcono
     * @return Artwork
     */
    public function setSujetIcono($sujetIcono)
    {
        $this->sujetIcono = $sujetIcono;

        return $this;
    }

    /**
     * Get sujetIcono
     *
     * @return string 
     */
    public function getSujetIcono()
    {
        return $this->sujetIcono;
    }

    /**
     * Set detail
     *
     * @param string $detail
     * @return Artwork
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string 
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return Artwork
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
     * @return Artwork
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
     * Set provenance
     *
     * @param string $provenance
     * @return Artwork
     */
    public function setProvenance($provenance)
    {
        $this->provenance = $provenance;

        return $this;
    }

    /**
     * Get provenance
     *
     * @return string 
     */
    public function getProvenance()
    {
        return $this->provenance;
    }

    /**
     * Set datation
     *
     * @param string $datation
     * @return Artwork
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
     * @return Artwork
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
     * @return Artwork
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
     * @return Artwork
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
     * Set lieuDeConservation
     *
     * @param string $lieuDeConservation
     * @return Artwork
     */
    public function setLieuDeConservation($lieuDeConservation)
    {
        $this->lieuDeConservation = $lieuDeConservation;

        return $this;
    }

    /**
     * Get lieuDeConservation
     *
     * @return string 
     */
    public function getLieuDeConservation()
    {
        return $this->lieuDeConservation;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Artwork
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
     * @return Artwork
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
     * @return Artwork
     */
    public function setCreateUser(\CAS\UserBundle\Entity\User $createUser)
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
     * @return Artwork
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
     * Set slug
     *
     * @param string $slug
     * @return Artwork
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
}
