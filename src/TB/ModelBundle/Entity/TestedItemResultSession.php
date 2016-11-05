<?php

namespace TB\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TestedItemResultSession
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TB\ModelBundle\Entity\TestedItemResultSessionRepository")
 */
class TestedItemResultSession
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
     * @ORM\ManyToOne(targetEntity="TB\ModelBundle\Entity\TestedSession")
     * @ORM\JoinColumn(nullable=false)
     */
    private $testedSession;

    /**
     * @ORM\OneToMany(targetEntity="TB\ModelBundle\Entity\TestedItemResult", mappedBy="testedItemResultSession", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $testedItemResults;

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
        $this->testedItemResults = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return TestedItemResultSession
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
     * Set testedSession
     *
     * @param \TB\ModelBundle\Entity\TestedSession $testedSession
     * @return TestedItemResultSession
     */
    public function setTestedSession(\TB\ModelBundle\Entity\TestedSession $testedSession)
    {
        $this->testedSession = $testedSession;

        return $this;
    }

    /**
     * Get testedSession
     *
     * @return \TB\ModelBundle\Entity\TestedSession 
     */
    public function getTestedSession()
    {
        return $this->testedSession;
    }

    /**
     * Add testedItemResults
     *
     * @param \TB\ModelBundle\Entity\TestedItemResult $testedItemResults
     * @return TestedItemResultSession
     */
    public function addTestedItemResult(\TB\ModelBundle\Entity\TestedItemResult $testedItemResults)
    {
        $this->testedItemResults[] = $testedItemResults;

        return $this;
    }

    /**
     * Remove testedItemResults
     *
     * @param \TB\ModelBundle\Entity\TestedItemResult $testedItemResults
     */
    public function removeTestedItemResult(\TB\ModelBundle\Entity\TestedItemResult $testedItemResults)
    {
        $this->testedItemResults->removeElement($testedItemResults);
    }

    /**
     * Get testedItemResults
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTestedItemResults()
    {
        return $this->testedItemResults;
    }
}
