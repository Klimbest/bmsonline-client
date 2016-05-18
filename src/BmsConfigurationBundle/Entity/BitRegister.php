<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BitRegister
 *
 * @ORM\Table(name="bit_register")
 * @ORM\Entity(repositoryClass="BmsConfigurationBundle\Entity\BitRegisterRepository")
 */
class BitRegister {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=16, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bit_value", type="boolean", nullable=false, options={"default"=false})
     */
    private $bitValue; 
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="position", type="boolean", nullable=false, options={"default"=false})
     */
    private $bitPosition;
    
    /**
     * 
     * @var \BmsConfigurationBundle\Entity\Register
     * 
     * @ORM\ManyToOne(targetEntity="BmsConfigurationBundle\Entity\Register", inversedBy="bit_registers")
     * @ORM\JoinColumn(name="register", referencedColumnName="id")
     */
    private $register;
    

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
     * Set name
     *
     * @param string $name
     *
     * @return BitRegister
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
     * Set description
     *
     * @param string $description
     *
     * @return BitRegister
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set register
     *
     * @param \BmsConfigurationBundle\Entity\Register $register
     *
     * @return BitRegister
     */
    public function setRegister(\BmsConfigurationBundle\Entity\Register $register = null)
    {
        $this->register = $register;

        return $this;
    }

    /**
     * Get register
     *
     * @return \BmsConfigurationBundle\Entity\Register
     */
    public function getRegister()
    {
        return $this->register;
    }

    /**
     * Set bitValue
     *
     * @param boolean $bitValue
     *
     * @return BitRegister
     */
    public function setBitValue($bitValue)
    {
        $this->bitValue = $bitValue;

        return $this;
    }

    /**
     * Get bitValue
     *
     * @return boolean
     */
    public function getBitValue()
    {
        return $this->bitValue;
    }

    /**
     * Set bitPosition
     *
     * @param boolean $bitPosition
     *
     * @return BitRegister
     */
    public function setBitPosition($bitPosition)
    {
        $this->bitPosition = $bitPosition;

        return $this;
    }

    /**
     * Get bitPosition
     *
     * @return boolean
     */
    public function getBitPosition()
    {
        return $this->bitPosition;
    }
}
