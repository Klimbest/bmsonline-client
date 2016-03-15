<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegisterArchiveData
 *
 * @ORM\Table(name="register_archive_data", indexes={@ORM\Index(name="time_of_insert", columns={"time_of_insert"})})
 * @ORM\Entity(repositoryClass="BmsConfigurationBundle\Entity\RegisterArchiveDataRepository")
 */
class RegisterArchiveData
{
    /**
     * @var string
     *
     * @ORM\Column(name="register_address", type="string", length=4, nullable=false)
     */
    private $registerAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="real_value_hex", type="string", length=4, nullable=true)
     */
    private $realValueHex;

    /**
     * @var integer
     *
     * @ORM\Column(name="real_value", type="integer", nullable=true)
     */
    private $realValue;

    /**
     * @var float
     *
     * @ORM\Column(name="fixed_value", type="float", precision=4, scale=1, nullable=true)
     */
    private $fixedValue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_of_insert", type="datetime", nullable=false)
     */
    private $timeOfInsert;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set registerAddress
     *
     * @param string $registerAddress
     * @return RegisterArchiveData
     */
    public function setRegisterAddress($registerAddress)
    {
        $this->registerAddress = $registerAddress;

        return $this;
    }

    /**
     * Get registerAddress
     *
     * @return string 
     */
    public function getRegisterAddress()
    {
        return $this->registerAddress;
    }

    /**
     * Set realValueHex
     *
     * @param string $realValueHex
     * @return RegisterArchiveData
     */
    public function setRealValueHex($realValueHex)
    {
        $this->realValueHex = $realValueHex;

        return $this;
    }

    /**
     * Get realValueHex
     *
     * @return string 
     */
    public function getRealValueHex()
    {
        return $this->realValueHex;
    }

    /**
     * Set realValue
     *
     * @param integer $realValue
     * @return RegisterArchiveData
     */
    public function setRealValue($realValue)
    {
        $this->realValue = $realValue;

        return $this;
    }

    /**
     * Get realValue
     *
     * @return integer 
     */
    public function getRealValue()
    {
        return $this->realValue;
    }

    /**
     * Set fixedValue
     *
     * @param float $fixedValue
     * @return RegisterArchiveData
     */
    public function setFixedValue($fixedValue)
    {
        $this->fixedValue = $fixedValue;

        return $this;
    }

    /**
     * Get fixedValue
     *
     * @return float 
     */
    public function getFixedValue()
    {
        return $this->fixedValue;
    }

    /**
     * Set timeOfInsert
     *
     * @param \DateTime $timeOfInsert
     * @return RegisterArchiveData
     */
    public function setTimeOfInsert($timeOfInsert)
    {
        $this->timeOfInsert = $timeOfInsert;

        return $this;
    }

    /**
     * Get timeOfInsert
     *
     * @return \DateTime 
     */
    public function getTimeOfInsert()
    {
        return $this->timeOfInsert;
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
