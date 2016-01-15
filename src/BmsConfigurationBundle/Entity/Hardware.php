<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hardware
 *
 * @ORM\Table(name="hardware")
 * @ORM\Entity
 */
class Hardware
{
    /**
     * @var integer
     *
     * @ORM\Column(name="raspi_key", type="integer", nullable=false)
     */
    private $raspiKey;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set raspiKey
     *
     * @param integer $raspiKey
     * @return Hardware
     */
    public function setRaspiKey($raspiKey)
    {
        $this->raspiKey = $raspiKey;

        return $this;
    }

    /**
     * Get raspiKey
     *
     * @return integer 
     */
    public function getRaspiKey()
    {
        return $this->raspiKey;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Hardware
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Hardware
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
