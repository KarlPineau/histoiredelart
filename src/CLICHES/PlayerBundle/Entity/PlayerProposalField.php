<?php

namespace CLICHES\PlayerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PlayerProposalField
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CLICHES\PlayerBundle\Entity\PlayerProposalFieldRepository")
 */
class PlayerProposalField
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
     * @ORM\ManyToOne(targetEntity="CLICHES\PlayerBundle\Entity\PlayerProposal", inversedBy="playerProposalFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $playerProposal;

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
     * @return PlayerProposalField
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
     * @return PlayerProposalField
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
     * @return PlayerProposalField
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
     * @return PlayerProposalField
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
}
