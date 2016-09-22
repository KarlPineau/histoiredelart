<?php

namespace CLICHES\PlayerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PlayerSuggest
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CLICHES\PlayerBundle\Entity\PlayerSuggestRepository")
 */
class PlayerSuggest
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
     * @ORM\ManyToOne(targetEntity="DATA\ImageBundle\Entity\View")
     * @ORM\JoinColumn(nullable=false)
     */
    private $view;

    /**
     * @var string
     *
     * @ORM\Column(name="playerSuggestContent", type="string", length=255, nullable=true)
     */
    private $playerSuggestContent;

    /**
     * @var string
     *
     * @ORM\Column(name="playerSuggestField", type="string", length=255, nullable=true)
     */
    private $playerSuggestField;


    /**
     * @var string
     *
     * @ORM\Column(name="playerSuggestTraitement", type="boolean", options="default: false;")
     */
    private $playerSuggestTraitement;


    /**
     * @var string
     *
     * @ORM\Column(name="playerSuggestAccept", type="boolean", nullable=true, options="default: false;")
     */
    private $playerSuggestAccept;

    /**
     * @var string
     *
     * @ORM\Column(name="playerSuggestAcceptExplain", type="string", length=255, nullable=true)
     */
    private $playerSuggestAcceptExplain;

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
    protected $createUser;

    /**
     * @var string
     *
     * @ORM\Column(name="ipCreateUser", type="string", length=255, nullable=true)
     */
    protected $ipCreateUser;


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
     * Set playerSuggestContent
     *
     * @param string $playerSuggestContent
     * @return PlayerSuggest
     */
    public function setPlayerSuggestContent($playerSuggestContent)
    {
        $this->playerSuggestContent = $playerSuggestContent;

        return $this;
    }

    /**
     * Get playerSuggestContent
     *
     * @return string 
     */
    public function getPlayerSuggestContent()
    {
        return $this->playerSuggestContent;
    }

    /**
     * Set playerSuggestField
     *
     * @param string $playerSuggestField
     * @return PlayerSuggest
     */
    public function setPlayerSuggestField($playerSuggestField)
    {
        $this->playerSuggestField = $playerSuggestField;

        return $this;
    }

    /**
     * Get playerSuggestField
     *
     * @return string 
     */
    public function getPlayerSuggestField()
    {
        return $this->playerSuggestField;
    }

    /**
     * Set playerSuggestTraitement
     *
     * @param boolean $playerSuggestTraitement
     * @return PlayerSuggest
     */
    public function setPlayerSuggestTraitement($playerSuggestTraitement)
    {
        $this->playerSuggestTraitement = $playerSuggestTraitement;

        return $this;
    }

    /**
     * Get playerSuggestTraitement
     *
     * @return boolean 
     */
    public function getPlayerSuggestTraitement()
    {
        return $this->playerSuggestTraitement;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return PlayerSuggest
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
     * Set view
     *
     * @param \DATA\ImageBundle\Entity\View $view
     * @return PlayerSuggest
     */
    public function setView(\DATA\ImageBundle\Entity\View $view)
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
     * Set ipCreateUser
     *
     * @param string $ipCreateUser
     * @return PlayerSuggest
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
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return PlayerSuggest
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
     * Set playerSuggestAccept
     *
     * @param boolean $playerSuggestAccept
     * @return PlayerSuggest
     */
    public function setPlayerSuggestAccept($playerSuggestAccept)
    {
        $this->playerSuggestAccept = $playerSuggestAccept;

        return $this;
    }

    /**
     * Get playerSuggestAccept
     *
     * @return boolean 
     */
    public function getPlayerSuggestAccept()
    {
        return $this->playerSuggestAccept;
    }

    /**
     * Set playerSuggestAcceptExplain
     *
     * @param string $playerSuggestAcceptExplain
     * @return PlayerSuggest
     */
    public function setPlayerSuggestAcceptExplain($playerSuggestAcceptExplain)
    {
        $this->playerSuggestAcceptExplain = $playerSuggestAcceptExplain;

        return $this;
    }

    /**
     * Get playerSuggestAcceptExplain
     *
     * @return string 
     */
    public function getPlayerSuggestAcceptExplain()
    {
        return $this->playerSuggestAcceptExplain;
    }
}
