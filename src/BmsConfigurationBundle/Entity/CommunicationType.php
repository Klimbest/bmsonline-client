<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CommunicationType
 *
 * @ORM\Table(name="communication_type", uniqueConstraints={@ORM\UniqueConstraint(name="unique_name", columns={"name"}), @ORM\UniqueConstraint(name="hardware", columns={"hardware_id"})})
 * @ORM\Entity
 */
class CommunicationType
{
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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10, nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="baud_rate", type="integer", nullable=true)
     */
    private $baudRate;

    /**
     * @var integer
     *
     * @ORM\Column(name="parity", type="integer", nullable=true)
     */
    private $parity;

    /**
     * @var integer
     *
     * @ORM\Column(name="data_bits", type="integer", nullable=true)
     */
    private $dataBits;

    /**
     * @var integer
     *
     * @ORM\Column(name="stop_bits", type="integer", nullable=true)
     */
    private $stopBits;

    /**
     * @var integer
     *
     * @ORM\Column(name="ip_address", type="integer", nullable=true)
     */
    private $ipAddress;

    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer", nullable=true)
     */
    private $port;

   /**
     * @var integer
     *
     * @ORM\Column(name="timeout_response", type="integer", nullable=false, options={"default"=300000})
     */
    private $timeoutResponse;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="timeout_between_send", type="integer", nullable=false, options={"default"=30000})
     */
    private $timeoutBetweenSend;
        
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=false)
     */
    private $updated;

    /**
     * @var \BmsConfigurationBundle\Entity\Hardware
     *
     * @ORM\OneToOne(targetEntity="BmsConfigurationBundle\Entity\Hardware")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hardware_id", referencedColumnName="id")
     * })
     */
    private $hardware_id;

    /**
     * @var \BmsConfigurationBundle\Entity\Device
     * 
     * @ORM\OneToMany(targetEntity="BmsConfigurationBundle\Entity\Device", mappedBy="communicationType")
     */
    private $devices;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->devices = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CommunicationType
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
     * Set type
     *
     * @param string $type
     * @return CommunicationType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set baudRate
     *
     * @param integer $baudRate
     * @return CommunicationType
     */
    public function setBaudRate($baudRate)
    {
        $this->baudRate = $baudRate;

        return $this;
    }

    /**
     * Get baudRate
     *
     * @return integer 
     */
    public function getBaudRate()
    {
        return $this->baudRate;
    }

    /**
     * Set parity
     *
     * @param integer $parity
     * @return CommunicationType
     */
    public function setParity($parity)
    {
        $this->parity = $parity;

        return $this;
    }

    /**
     * Get parity
     *
     * @return integer 
     */
    public function getParity()
    {
        return $this->parity;
    }

    /**
     * Set dataBits
     *
     * @param integer $dataBits
     * @return CommunicationType
     */
    public function setDataBits($dataBits)
    {
        $this->dataBits = $dataBits;

        return $this;
    }

    /**
     * Get dataBits
     *
     * @return integer 
     */
    public function getDataBits()
    {
        return $this->dataBits;
    }

    /**
     * Set stopBits
     *
     * @param integer $stopBits
     * @return CommunicationType
     */
    public function setStopBits($stopBits)
    {
        $this->stopBits = $stopBits;

        return $this;
    }

    /**
     * Get stopBits
     *
     * @return integer 
     */
    public function getStopBits()
    {
        return $this->stopBits;
    }

    /**
     * Set ipAddress
     *
     * @param integer $ipAddress
     * @return CommunicationType
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return integer 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set port
     *
     * @param integer $port
     * @return CommunicationType
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return CommunicationType
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
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
     * Set hardware_id
     *
     * @param Hardware $hardwareId
     * @return CommunicationType
     */
    public function setHardwareId(Hardware $hardwareId = null)
    {
        $this->hardware_id = $hardwareId;

        return $this;
    }

    /**
     * Get hardware_id
     *
     * @return Hardware 
     */
    public function getHardwareId()
    {
        return $this->hardware_id;
    }

    /**
     * Add devices
     *
     * @param Device $devices
     * @return CommunicationType
     */
    public function addDevice(Device $devices)
    {
        $this->devices[] = $devices;

        return $this;
    }

    /**
     * Remove devices
     *
     * @param Device $devices
     */
    public function removeDevice(Device $devices)
    {
        $this->devices->removeElement($devices);
    }

    /**
     * Get devices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * Set timeoutResponse
     *
     * @param integer $timeoutResponse
     *
     * @return CommunicationType
     */
    public function setTimeoutResponse($timeoutResponse)
    {
        $this->timeoutResponse = $timeoutResponse;

        return $this;
    }

    /**
     * Get timeoutResponse
     *
     * @return integer
     */
    public function getTimeoutResponse()
    {
        return $this->timeoutResponse;
    }

    /**
     * Set timeoutBetweenSend
     *
     * @param integer $timeoutBetweenSend
     *
     * @return CommunicationType
     */
    public function setTimeoutBetweenSend($timeoutBetweenSend)
    {
        $this->timeoutBetweenSend = $timeoutBetweenSend;

        return $this;
    }

    /**
     * Get timeoutBetweenSend
     *
     * @return integer
     */
    public function getTimeoutBetweenSend()
    {
        return $this->timeoutBetweenSend;
    }

    
}
