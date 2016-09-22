<?php

namespace CAS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserPreferences
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CAS\UserBundle\Entity\UserPreferencesRepository")
 */
class UserPreferences
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
     * @ORM\ManyToOne(targetEntity="CAS\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * Paramètre pour recevoir la newsletter de Histoiredelart.fr
     * @var boolean
     *
     * @ORM\Column(name="histoiredelartNewsletter", type="boolean")
     */
    private $histoiredelartNewsletter;

    /**
     * Paramètre pour recevoir des mails lors de la validation de l'import d'un dataset sur Data
     * @var boolean
     *
     * @ORM\Column(name="dataDatasetConfirmation", type="boolean")
     */
    private $dataDatasetConfirmation;

    /**
     * Paramètre pour recevoir des mails lors de la validation d'une modification de Data
     * @var boolean
     *
     * @ORM\Column(name="dataReportingConfirmation", type="boolean")
     */
    private $dataReportingConfirmation;

    /**
     * Paramètre pour recevoir des mails lors de la validation d'une modification de Clichés
     * @var boolean
     *
     * @ORM\Column(name="clichesReportingConfirmation", type="boolean")
     */
    private $clichesReportingConfirmation;

    /**
     * Paramètre pour le calcul des sessions de cliches en fonction des précédentes sessions
     * @var boolean
     *
     * @ORM\Column(name="clichesLogfileComputing", type="boolean")
     */
    private $clichesLogfileComputing;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createDate", type="datetime", nullable=true)
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
     * Set histoiredelartNewsletter
     *
     * @param boolean $histoiredelartNewsletter
     * @return UserPreferences
     */
    public function setHistoiredelartNewsletter($histoiredelartNewsletter)
    {
        $this->histoiredelartNewsletter = $histoiredelartNewsletter;

        return $this;
    }

    /**
     * Get histoiredelartNewsletter
     *
     * @return boolean 
     */
    public function getHistoiredelartNewsletter()
    {
        return $this->histoiredelartNewsletter;
    }

    /**
     * Set dataDatasetConfirmation
     *
     * @param boolean $dataDatasetConfirmation
     * @return UserPreferences
     */
    public function setDataDatasetConfirmation($dataDatasetConfirmation)
    {
        $this->dataDatasetConfirmation = $dataDatasetConfirmation;

        return $this;
    }

    /**
     * Get dataDatasetConfirmation
     *
     * @return boolean 
     */
    public function getDataDatasetConfirmation()
    {
        return $this->dataDatasetConfirmation;
    }

    /**
     * Set dataReportingConfirmation
     *
     * @param boolean $dataReportingConfirmation
     * @return UserPreferences
     */
    public function setDataReportingConfirmation($dataReportingConfirmation)
    {
        $this->dataReportingConfirmation = $dataReportingConfirmation;

        return $this;
    }

    /**
     * Get dataReportingConfirmation
     *
     * @return boolean 
     */
    public function getDataReportingConfirmation()
    {
        return $this->dataReportingConfirmation;
    }

    /**
     * Set clichesReportingConfirmation
     *
     * @param boolean $clichesReportingConfirmation
     * @return UserPreferences
     */
    public function setClichesReportingConfirmation($clichesReportingConfirmation)
    {
        $this->clichesReportingConfirmation = $clichesReportingConfirmation;

        return $this;
    }

    /**
     * Get clichesReportingConfirmation
     *
     * @return boolean 
     */
    public function getClichesReportingConfirmation()
    {
        return $this->clichesReportingConfirmation;
    }

    /**
     * Set clichesLogfileComputing
     *
     * @param boolean $clichesLogfileComputing
     * @return UserPreferences
     */
    public function setClichesLogfileComputing($clichesLogfileComputing)
    {
        $this->clichesLogfileComputing = $clichesLogfileComputing;

        return $this;
    }

    /**
     * Get clichesLogfileComputing
     *
     * @return boolean 
     */
    public function getClichesLogfileComputing()
    {
        return $this->clichesLogfileComputing;
    }

    /**
     * Set user
     *
     * @param \CAS\UserBundle\Entity\User $user
     * @return UserPreferences
     */
    public function setUser(\CAS\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CAS\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return UserPreferences
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
     * @return UserPreferences
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
