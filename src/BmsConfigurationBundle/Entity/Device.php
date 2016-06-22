<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Device
 *
 * @ORM\Table(name="device", uniqueConstraints={@ORM\UniqueConstraint(name="unique_modbus_address", columns={"communication_type_id", "modbus_address"})})
 * @ORM\Entity
 * @UniqueEntity(fields={"communicationType", "modbusAddress"})
 */
class Device
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=16, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="modbus_address", type="integer", nullable=false)
     */
    private $modbusAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \BmsConfigurationBundle\Entity\CommunicationType
     *
     * @ORM\ManyToOne(targetEntity="BmsConfigurationBundle\Entity\CommunicationType", inversedBy="devices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="communication_type_id", referencedColumnName="id")
     * })
     */
    private $communicationType;
    
    /**
     * @var Register
     * 
     * @ORM\OneToMany(targetEntity="Register", mappedBy="device")
     */
    private $registers;

    
    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"default"=false})
     */
    private $active;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="scan_state", type="integer", nullable=true)
     */
    private $scanState;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="report", type="boolean", nullable=false, options={"default"=false})
     */
    private $report;
    
    /**
     * @var string
     *
     * @ORM\Column(name="localization", type="string", length=255, nullable=true)
     */
    private $localization;
     
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }
    
   

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Device
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
     * @return Device
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
     * Set modbusAddress
     *
     * @param integer $modbusAddress
     *
     * @return Device
     */
    public function setModbusAddress($modbusAddress)
    {
        $this->modbusAddress = $modbusAddress;

        return $this;
    }

    /**
     * Get modbusAddress
     *
     * @return integer
     */
    public function getModbusAddress()
    {
        return $this->modbusAddress;
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

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Device
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
     * Set scanState
     *
     * @param integer $scanState
     *
     * @return Device
     */
    public function setScanState($scanState)
    {
        $this->scanState = $scanState;

        return $this;
    }

    /**
     * Get scanState
     *
     * @return integer
     */
    public function getScanState()
    {
        return $this->scanState;
    }

    /**
     * Set localization
     *
     * @param string $localization
     *
     * @return Device
     */
    public function setLocalization($localization)
    {
        $this->localization = $localization;

        return $this;
    }

    /**
     * Get localization
     *
     * @return string
     */
    public function getLocalization()
    {
        return $this->localization;
    }

    /**
     * Set communicationType
     *
     * @param \BmsConfigurationBundle\Entity\CommunicationType $communicationType
     *
     * @return Device
     */
    public function setCommunicationType(\BmsConfigurationBundle\Entity\CommunicationType $communicationType = null)
    {
        $this->communicationType = $communicationType;

        return $this;
    }

    /**
     * Get communicationType
     *
     * @return \BmsConfigurationBundle\Entity\CommunicationType
     */
    public function getCommunicationType()
    {
        return $this->communicationType;
    }

    /**
     * Add register
     *
     * @param \BmsConfigurationBundle\Entity\Register $register
     *
     * @return Device
     */
    public function addRegister(\BmsConfigurationBundle\Entity\Register $register)
    {
        $this->registers[] = $register;

        return $this;
    }

    /**
     * Remove register
     *
     * @param \BmsConfigurationBundle\Entity\Register $register
     */
    public function removeRegister(\BmsConfigurationBundle\Entity\Register $register)
    {
        $this->registers->removeElement($register);
    }

    /**
     * Get registers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegisters()
    {
        return $this->registers;
    }

    /**
     * Set report
     *
     * @param boolean $report
     *
     * @return Device
     */
    public function setReport($report)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * Get report
     *
     * @return boolean
     */
    public function getReport()
    {
        return $this->report;
    }
}
