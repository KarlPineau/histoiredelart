<?php

namespace CLICHES\PlayerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PlayerProposalField
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class PlayerProposalChoiceValue
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
     * @ORM\ManyToOne(targetEntity="CLICHES\PlayerBundle\Entity\PlayerProposalChoice", inversedBy="playerProposalChoiceValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $playerProposalChoice;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isTrue", type="boolean")
     */
    private $isTrue;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=255, nullable=false)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="DATA\DataBundle\Entity\Entity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entity;

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
     * Set field
     *
     * @param string $field
     * @return PlayerProposalChoiceValue
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return string 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return PlayerProposalChoiceValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return PlayerProposalChoiceValue
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
     * Set playerProposalChoice
     *
     * @param \CLICHES\PlayerBundle\Entity\PlayerProposalChoice $playerProposalChoice
     * @return PlayerProposalChoiceValue
     */
    public function setPlayerProposalChoice(\CLICHES\PlayerBundle\Entity\PlayerProposalChoice $playerProposalChoice)
    {
        $this->playerProposalChoice = $playerProposalChoice;

        return $this;
    }

    /**
     * Get playerProposalChoice
     *
     * @return \CLICHES\PlayerBundle\Entity\PlayerProposalChoice 
     */
    public function getPlayerProposalChoice()
    {
        return $this->playerProposalChoice;
    }

    /**
     * Set entity
     *
     * @param \DATA\DataBundle\Entity\Entity $entity
     * @return PlayerProposalChoiceValue
     */
    public function setEntity(\Data\DataBundle\Entity\Entity $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return \DATA\DataBundle\Entity\Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set isTrue
     *
     * @param boolean $isTrue
     * @return PlayerProposalChoiceValue
     */
    public function setIsTrue($isTrue)
    {
        $this->isTrue = $isTrue;

        return $this;
    }

    /**
     * Get isTrue
     *
     * @return boolean 
     */
    public function getIsTrue()
    {
        return $this->isTrue;
    }
}
