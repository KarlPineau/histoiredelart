<?php

namespace CLICHES\PlayerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PlayerZoomView
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CLICHES\PlayerBundle\Entity\PlayerZoomViewRepository")
 */
class PlayerZoomView
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
     * @ORM\ManyToOne(targetEntity="CLICHES\PlayerBundle\Entity\PlayerOeuvre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $playerOeuvre;

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
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return PlayerZoomView
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
     * Set playerOeuvre
     *
     * @param \CLICHES\PlayerBundle\Entity\PlayerOeuvre $playerOeuvre
     * @return PlayerZoomView
     */
    public function setPlayerOeuvre(\CLICHES\PlayerBundle\Entity\PlayerOeuvre $playerOeuvre)
    {
        $this->playerOeuvre = $playerOeuvre;

        return $this;
    }

    /**
     * Get playerOeuvre
     *
     * @return \CLICHES\PlayerBundle\Entity\PlayerOeuvre 
     */
    public function getPlayerOeuvre()
    {
        return $this->playerOeuvre;
    }
}
