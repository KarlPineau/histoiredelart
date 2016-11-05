<?php

namespace TB\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TB\ModelBundle\Entity\TagRepository")
 */
class Tag
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
     * @var \stdClass
     *
     * @ORM\Column(name="testedGames", type="object")
     */
    private $testedGames;


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
     * Set testedGames
     *
     * @param \stdClass $testedGames
     * @return Tag
     */
    public function setTestedGames($testedGames)
    {
        $this->testedGames = $testedGames;

        return $this;
    }

    /**
     * Get testedGames
     *
     * @return \stdClass 
     */
    public function getTestedGames()
    {
        return $this->testedGames;
    }
}
