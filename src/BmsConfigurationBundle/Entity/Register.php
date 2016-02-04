<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Register
 *
 * @ORM\Table(name="register", uniqueConstraints={@ORM\UniqueConstraint(name="device_id", columns={"device_id", "register_address", "function"}),
 *                                                @ORM\UniqueConstraint(name="reg_name", columns={"name"}) }, 
 *                             indexes={@ORM\Index(name="device", columns={"device_id"}),
 *                                      @ORM\Index(name="name", columns={"name"})
 * })
 * @ORM\Entity
 * @UniqueEntity(fields="name", message="Nazwa rejestru musi być unikalna w obrębie całego systemu.")
 */
class Register
{
    /**
     * @var string
     *
     * @ORM\Column(name="register_address", type="string", length=4, nullable=false)
     */
    private $registerAddress;

    /**
     *
     * @var string
     *  
     * @ORM\Column(name="function", type="string", length=2, nullable=false)
     */
    private $function;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="scan_queue", type="integer", nullable=false)
     */
    private $scanQueue;

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
     * @var string
     *
     * @ORM\Column(name="display_suffix", type="string", length=5, nullable=true)
     */
    private $displaySuffix;

    /**
     * @var decimal
     *
     * @ORM\Column(name="modificator_read", type="decimal", precision=9, scale=6, nullable=true)
     */
    private $modificatorRead;

    /**
     * @var integer
     *
     * @ORM\Column(name="modificator_write", type="integer", nullable=true)
     */
    private $modificatorWrite;

    /**
     * @var boolean
     *
     * @ORM\Column(name="archive", type="boolean", nullable=false, options={"default"=true})
     */
    private $archive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"default"=true})
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
     * @var \BmsConfigurationBundle\Entity\Device
     *
     * @ORM\ManyToOne(targetEntity="BmsConfigurationBundle\Entity\Device", inversedBy="registers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     * })
     */
    private $device;

    /**
     *
     * @var type \BmsConfigurationBundle\Entity\RegisterCurrentData
     * 
     * @ORM\OneToOne(targetEntity="RegisterCurrentData", mappedBy="register")
     */ 
    private $registerCurrentData;

    /**
     * Set registerAddress
     *
     * @param string $registerAddress
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
     * Set name
     *
     * @param string $name
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
     * Set description
     *
     * @param string $description
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
     * Set displaySuffix
     *
     * @param string $displaySuffix
     * @return Register
     */
    public function setDisplaySuffix($displaySuffix)
    {
        $this->displaySuffix = $displaySuffix;

        return $this;
    }

    /**
     * Get displaySuffix
     *
     * @return string 
     */
    public function getDisplaySuffix()
    {
        return $this->displaySuffix;
    }

    /**
     * Set modificatorRead
     *
     * @param integer $modificatorRead
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
     * @return integer 
     */
    public function getModificatorRead()
    {
        return $this->modificatorRead;
    }

    /**
     * Set modificatorWrite
     *
     * @param integer $modificatorWrite
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
     * @return integer 
     */
    public function getModificatorWrite()
    {
        return $this->modificatorWrite;
    }

    /**
     * Set archive
     *
     * @param boolean $archive
     * @return Register
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return boolean 
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Register
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

    /**
     * Set device
     *
     * @param \BmsConfigurationBundle\Entity\Device $device
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
     * Set scanQueue
     *
     * @param integer $scanQueue
     * @return Register
     */
    public function setScanQueue($scanQueue)
    {
        $this->scanQueue = $scanQueue;

        return $this;
    }

    /**
     * Get scanQueue
     *
     * @return integer 
     */
    public function getScanQueue()
    {
        return $this->scanQueue;
    }

    /**
     * Set function
     *
     * @param string $function
     *
     * @return Register
     */
    public function setFunction($function)
    {
        $this->function = $function;

        return $this;
    }

    /**
     * Get function
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }
}
