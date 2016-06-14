<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Register
 *
 * @ORM\Table(name="register", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="device_id", columns={"device_id", "register_address", "read_function"}),
 *      @ORM\UniqueConstraint(name="reg_name", columns={"name"}) 
 * })
 * 
 * @ORM\Entity(repositoryClass="BmsConfigurationBundle\Entity\RegisterRepository")
 * @UniqueEntity(fields="name", message="Nazwa rejestru musi być unikalna w obrębie całego systemu.")
 */
class Register {

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
     * @ORM\Column(name="name", type="string", length=20, nullable=false, unique=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="register_address", type="string", length=4, nullable=false)
     */
    private $registerAddress;

    /**
     * @var string
     *  
     * @ORM\Column(name="read_function", type="string", length=2, nullable=false)
     */
    private $readFunction;
    
    /**
     * @var string
     *  
     * @ORM\Column(name="write_function", type="string", length=2, nullable=true)
     */
    
    private $writeFunction;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="register_size", type="integer", nullable=false)
     */
    private $registerSize;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="description2", type="string", length=255, nullable=true)
     */
    private $description2;

    /**
     * @var decimal
     *
     * @ORM\Column(name="modificator_read", type="decimal", precision=15, scale=8, nullable=false)
     */
    private $modificatorRead;

    /**
     * @var integer
     *
     * @ORM\Column(name="modificator_write", type="decimal", precision=15, scale=8, nullable=false)
     */
    private $modificatorWrite;

    /**
     * @var boolean
     *
     * @ORM\Column(name="write_register", type="boolean", nullable=false, options={"default"=false})
     */
    private $writeRegister;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="write_limit_min", type="decimal", precision=15, scale=8, nullable=false)
     */
    private $writeLimitMin;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="write_limit_max", type="decimal", precision=15, scale=8, nullable=false)
     */
    private $writeLimitMax;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="archive_register", type="boolean", nullable=false, options={"default"=false})
     */
    private $archiveRegister;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_register", type="boolean", nullable=false, options={"default"=false})
     */
    private $activeRegister;

    /**
     * @var boolean
     *
     * @ORM\Column(name="alarm_register", type="boolean", nullable=false, options={"default"=false})
     */
    private $alarmRegister;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bit_register", type="boolean", nullable=false, options={"default"=false})
     */
    private $bitRegister;

    /**
     * @var Device
     *
     * @ORM\ManyToOne(targetEntity="BmsConfigurationBundle\Entity\Device", inversedBy="registers")
     * @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     */
    private $device;

    /**
     *
     * @var type RegisterCurrentData
     * 
     * @ORM\OneToOne(targetEntity="RegisterCurrentData", mappedBy="register", cascade={"remove"})
     */
    private $registerCurrentData;

    /**
     *
     * @var type RegisterArchiveData
     * 
     * @ORM\OneToMany(targetEntity="RegisterArchiveData", mappedBy="register", cascade={"remove"})
     */
    private $registerArchiveData;

    /**
     * 
     * @var type \BmsConfigurationBundle\Entity\BitRegister
     * 
     * @ORM\OneToMany(targetEntity="BitRegister", mappedBy="register", cascade={"remove"})
     */
    private $bitRegisters;

    /**
     *
     * @var type \BmsConfigurationBundle\Entity\RegisterWriteData
     * 
     * @ORM\OneToMany(targetEntity="RegisterWriteData", mappedBy="register")
     */
    private $registerWriteData;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->registerArchiveData = new ArrayCollection();
        $this->bitRegisters = new ArrayCollection();
        $this->registerWriteData = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return Register
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
     * Set registerAddress
     *
     * @param string $registerAddress
     *
     * @return Register
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
     * Set readFunction
     *
     * @param string $readFunction
     *
     * @return Register
     */
    public function setReadFunction($readFunction)
    {
        $this->readFunction = $readFunction;

        return $this;
    }

    /**
     * Get readFunction
     *
     * @return string
     */
    public function getReadFunction()
    {
        return $this->readFunction;
    }

    /**
     * Set writeFunction
     *
     * @param string $writeFunction
     *
     * @return Register
     */
    public function setWriteFunction($writeFunction)
    {
        $this->writeFunction = $writeFunction;

        return $this;
    }

    /**
     * Get writeFunction
     *
     * @return string
     */
    public function getWriteFunction()
    {
        return $this->writeFunction;
    }

    /**
     * Set registerSize
     *
     * @param integer $registerSize
     *
     * @return Register
     */
    public function setRegisterSize($registerSize)
    {
        $this->registerSize = $registerSize;

        return $this;
    }

    /**
     * Get registerSize
     *
     * @return integer
     */
    public function getRegisterSize()
    {
        return $this->registerSize;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Register
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
     * Set description2
     *
     * @param string $description2
     *
     * @return Register
     */
    public function setDescription2($description2)
    {
        $this->description2 = $description2;

        return $this;
    }

    /**
     * Get description2
     *
     * @return string
     */
    public function getDescription2()
    {
        return $this->description2;
    }

    /**
     * Set modificatorRead
     *
     * @param string $modificatorRead
     *
     * @return Register
     */
    public function setModificatorRead($modificatorRead)
    {
        $this->modificatorRead = $modificatorRead;

        return $this;
    }

    /**
     * Get modificatorRead
     *
     * @return string
     */
    public function getModificatorRead()
    {
        return $this->modificatorRead;
    }

    /**
     * Set modificatorWrite
     *
     * @param string $modificatorWrite
     *
     * @return Register
     */
    public function setModificatorWrite($modificatorWrite)
    {
        $this->modificatorWrite = $modificatorWrite;

        return $this;
    }

    /**
     * Get modificatorWrite
     *
     * @return string
     */
    public function getModificatorWrite()
    {
        return $this->modificatorWrite;
    }

    /**
     * Set writeRegister
     *
     * @param boolean $writeRegister
     *
     * @return Register
     */
    public function setWriteRegister($writeRegister)
    {
        $this->writeRegister = $writeRegister;

        return $this;
    }

    /**
     * Get writeRegister
     *
     * @return boolean
     */
    public function getWriteRegister()
    {
        return $this->writeRegister;
    }

    /**
     * Set archiveRegister
     *
     * @param boolean $archiveRegister
     *
     * @return Register
     */
    public function setArchiveRegister($archiveRegister)
    {
        $this->archiveRegister = $archiveRegister;

        return $this;
    }

    /**
     * Get archiveRegister
     *
     * @return boolean
     */
    public function getArchiveRegister()
    {
        return $this->archiveRegister;
    }

    /**
     * Set activeRegister
     *
     * @param boolean $activeRegister
     *
     * @return Register
     */
    public function setActiveRegister($activeRegister)
    {
        $this->activeRegister = $activeRegister;

        return $this;
    }

    /**
     * Get activeRegister
     *
     * @return boolean
     */
    public function getActiveRegister()
    {
        return $this->activeRegister;
    }

    /**
     * Set alarmRegister
     *
     * @param boolean $alarmRegister
     *
     * @return Register
     */
    public function setAlarmRegister($alarmRegister)
    {
        $this->alarmRegister = $alarmRegister;

        return $this;
    }

    /**
     * Get alarmRegister
     *
     * @return boolean
     */
    public function getAlarmRegister()
    {
        return $this->alarmRegister;
    }

    /**
     * Set bitRegister
     *
     * @param boolean $bitRegister
     *
     * @return Register
     */
    public function setBitRegister($bitRegister)
    {
        $this->bitRegister = $bitRegister;

        return $this;
    }

    /**
     * Get bitRegister
     *
     * @return boolean
     */
    public function getBitRegister()
    {
        return $this->bitRegister;
    }

    /**
     * Set device
     *
     * @param \BmsConfigurationBundle\Entity\Device $device
     *
     * @return Register
     */
    public function setDevice(\BmsConfigurationBundle\Entity\Device $device = null)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return \BmsConfigurationBundle\Entity\Device
     */
    public function getDevice()
    {
        return $this->device;
    }

    /**
     * Set registerCurrentData
     *
     * @param \BmsConfigurationBundle\Entity\RegisterCurrentData $registerCurrentData
     *
     * @return Register
     */
    public function setRegisterCurrentData(\BmsConfigurationBundle\Entity\RegisterCurrentData $registerCurrentData = null)
    {
        $this->registerCurrentData = $registerCurrentData;

        return $this;
    }

    /**
     * Get registerCurrentData
     *
     * @return \BmsConfigurationBundle\Entity\RegisterCurrentData
     */
    public function getRegisterCurrentData()
    {
        return $this->registerCurrentData;
    }

    /**
     * Add registerArchiveDatum
     *
     * @param \BmsConfigurationBundle\Entity\RegisterArchiveData $registerArchiveDatum
     *
     * @return Register
     */
    public function addRegisterArchiveDatum(\BmsConfigurationBundle\Entity\RegisterArchiveData $registerArchiveDatum)
    {
        $this->registerArchiveData[] = $registerArchiveDatum;

        return $this;
    }

    /**
     * Remove registerArchiveDatum
     *
     * @param \BmsConfigurationBundle\Entity\RegisterArchiveData $registerArchiveDatum
     */
    public function removeRegisterArchiveDatum(\BmsConfigurationBundle\Entity\RegisterArchiveData $registerArchiveDatum)
    {
        $this->registerArchiveData->removeElement($registerArchiveDatum);
    }

    /**
     * Get registerArchiveData
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegisterArchiveData()
    {
        return $this->registerArchiveData;
    }

    /**
     * Add bitRegister
     *
     * @param \BmsConfigurationBundle\Entity\BitRegister $bitRegister
     *
     * @return Register
     */
    public function addBitRegister(\BmsConfigurationBundle\Entity\BitRegister $bitRegister)
    {
        $this->bitRegisters[] = $bitRegister;

        return $this;
    }

    /**
     * Remove bitRegister
     *
     * @param \BmsConfigurationBundle\Entity\BitRegister $bitRegister
     */
    public function removeBitRegister(\BmsConfigurationBundle\Entity\BitRegister $bitRegister)
    {
        $this->bitRegisters->removeElement($bitRegister);
    }

    /**
     * Get bitRegisters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBitRegisters()
    {
        return $this->bitRegisters;
    }

    /**
     * Add registerWriteDatum
     *
     * @param \BmsConfigurationBundle\Entity\RegisterWriteData $registerWriteDatum
     *
     * @return Register
     */
    public function addRegisterWriteDatum(\BmsConfigurationBundle\Entity\RegisterWriteData $registerWriteDatum)
    {
        $this->registerWriteData[] = $registerWriteDatum;

        return $this;
    }

    /**
     * Remove registerWriteDatum
     *
     * @param \BmsConfigurationBundle\Entity\RegisterWriteData $registerWriteDatum
     */
    public function removeRegisterWriteDatum(\BmsConfigurationBundle\Entity\RegisterWriteData $registerWriteDatum)
    {
        $this->registerWriteData->removeElement($registerWriteDatum);
    }

    /**
     * Get registerWriteData
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegisterWriteData()
    {
        return $this->registerWriteData;
    }

    /**
     * Set writeLimitMin
     *
     * @param string $writeLimitMin
     *
     * @return Register
     */
    public function setWriteLimitMin($writeLimitMin)
    {
        $this->writeLimitMin = $writeLimitMin;

        return $this;
    }

    /**
     * Get writeLimitMin
     *
     * @return string
     */
    public function getWriteLimitMin()
    {
        return $this->writeLimitMin;
    }

    /**
     * Set writeLimitMax
     *
     * @param string $writeLimitMax
     *
     * @return Register
     */
    public function setWriteLimitMax($writeLimitMax)
    {
        $this->writeLimitMax = $writeLimitMax;

        return $this;
    }

    /**
     * Get writeLimitMax
     *
     * @return string
     */
    public function getWriteLimitMax()
    {
        return $this->writeLimitMax;
    }
}
