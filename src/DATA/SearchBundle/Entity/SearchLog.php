<?php

namespace DATA\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SearchLog
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DATA\SearchBundle\Entity\SearchLogRepository")
 */
class SearchLog
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
     * @var string
     *
     * @ORM\Column(name="searchValue", type="string", length=255)
     */
    private $searchValue;
    /**
     * @var integer
     *
     * @ORM\Column(name="searchNumberResults", type="integer")
     */
    private $searchNumberResults;

    /**
     * @ORM\ManyToOne(targetEntity="CAS\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $createUser;

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
     * Set searchValue
     *
     * @param string $searchValue
     * @return SearchLog
     */
    public function setSearchValue($searchValue)
    {
        $this->searchValue = $searchValue;

        return $this;
    }

    /**
     * Get searchValue
     *
     * @return string 
     */
    public function getSearchValue()
    {
        return $this->searchValue;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return SearchLog
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
     * Set createUser
     *
     * @param \CAS\UserBundle\Entity\User $createUser
     * @return SearchLog
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
     * Set searchNumberResults
     *
     * @param integer $searchNumberResults
     * @return SearchLog
     */
    public function setSearchNumberResults($searchNumberResults)
    {
        $this->searchNumberResults = $searchNumberResults;

        return $this;
    }

    /**
     * Get searchNumberResults
     *
     * @return integer 
     */
    public function getSearchNumberResults()
    {
        return $this->searchNumberResults;
    }
}
