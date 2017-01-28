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
class PlayerProposalChoice
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
     * @ORM\ManyToOne(targetEntity="CLICHES\PlayerBundle\Entity\PlayerProposal", inversedBy="playerProposalChoices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $playerProposal;

    /**
     * @ORM\OneToMany(targetEntity="CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue", mappedBy="playerProposalChoice", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $playerProposalChoiceValues;

    /**
     * @var string
     *
     * @ORM\Column(name="correctAnswer", type="boolean", nullable=true)
     */
    private $correctAnswer;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=255, nullable=false)
     */
    private $field;

    /**
     * @ORM\ManyToOne(targetEntity="CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue")
     * @ORM\JoinColumn(nullable=true)
     */
    private $playerProposalChoiceValueSelected;

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
        $this->playerProposalChoiceValues = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set field
     *
     * @param string $field
     * @return PlayerProposalChoice
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
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return PlayerProposalChoice
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
     * Set playerProposal
     *
     * @param \CLICHES\PlayerBundle\Entity\PlayerProposal $playerProposal
     * @return PlayerProposalChoice
     */
    public function setPlayerProposal(\CLICHES\PlayerBundle\Entity\PlayerProposal $playerProposal)
    {
        $this->playerProposal = $playerProposal;

        return $this;
    }

    /**
     * Get playerProposal
     *
     * @return \CLICHES\PlayerBundle\Entity\PlayerProposal 
     */
    public function getPlayerProposal()
    {
        return $this->playerProposal;
    }

    /**
     * Add playerProposalChoiceValues
     *
     * @param \CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue $playerProposalChoiceValues
     * @return PlayerProposalChoice
     */
    public function addPlayerProposalChoiceValue(\CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue $playerProposalChoiceValues)
    {
        $this->playerProposalChoiceValues[] = $playerProposalChoiceValues;

        return $this;
    }

    /**
     * Remove playerProposalChoiceValues
     *
     * @param \CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue $playerProposalChoiceValues
     */
    public function removePlayerProposalChoiceValue(\CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue $playerProposalChoiceValues)
    {
        $this->playerProposalChoiceValues->removeElement($playerProposalChoiceValues);
    }

    /**
     * Get playerProposalChoiceValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayerProposalChoiceValues()
    {
        return $this->playerProposalChoiceValues;
    }

    /**
     * Set playerProposalChoiceValueSelected
     *
     * @param \CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue $playerProposalChoiceValueSelected
     * @return PlayerProposalChoice
     */
    public function setPlayerProposalChoiceValueSelected(\CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue $playerProposalChoiceValueSelected = null)
    {
        $this->playerProposalChoiceValueSelected = $playerProposalChoiceValueSelected;

        return $this;
    }

    /**
     * Get playerProposalChoiceValueSelected
     *
     * @return \CLICHES\PlayerBundle\Entity\PlayerProposalChoiceValue 
     */
    public function getPlayerProposalChoiceValueSelected()
    {
        return $this->playerProposalChoiceValueSelected;
    }

    /**
     * Set correctAnswer
     *
     * @param boolean $correctAnswer
     * @return PlayerProposalChoice
     */
    public function setCorrectAnswer($correctAnswer)
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }

    /**
     * Get correctAnswer
     *
     * @return boolean 
     */
    public function getCorrectAnswer()
    {
        return $this->correctAnswer;
    }
}
