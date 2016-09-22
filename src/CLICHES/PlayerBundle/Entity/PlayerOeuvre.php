<?php

namespace CLICHES\PlayerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PlayerOeuvre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CLICHES\PlayerBundle\Entity\PlayerOeuvreRepository")
 */
class PlayerOeuvre
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
     * @ORM\ManyToOne(targetEntity="CLICHES\PlayerBundle\Entity\PlayerSession")
     * @ORM\JoinColumn(nullable=false)
     */
    private $playerSession;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\ImageBundle\Entity\View")
     * @ORM\JoinColumn(nullable=true)
     */
    private $view;

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
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return PlayerOeuvre
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
     * Set playerSession
     *
     * @param \CLICHES\PlayerBundle\Entity\PlayerSession $playerSession
     * @return PlayerOeuvre
     */
    public function setPlayerSession(\CLICHES\PlayerBundle\Entity\PlayerSession $playerSession)
    {
        $this->playerSession = $playerSession;

        return $this;
    }

    /**
     * Get playerSession
     *
     * @return \CLICHES\PlayerBundle\Entity\PlayerSession 
     */
    public function getPlayerSession()
    {
        return $this->playerSession;
    }

    /**
     * Set view
     *
     * @param \DATA\ImageBundle\Entity\View $view
     * @return PlayerOeuvre
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
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return PlayerOeuvre
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
}
