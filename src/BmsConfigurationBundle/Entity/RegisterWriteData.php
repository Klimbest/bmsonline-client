<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegisterWriteData
 *
 * @ORM\Table(name="register_write_data")
 * @ORM\Entity
 */
class RegisterWriteData
{
    /**
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=4, nullable=true)
     */
    private $value;

    /**
     * @var \BmsConfigurationBundle\Entity\Register
     *
     * @ORM\ManyToOne(targetEntity="BmsConfigurationBundle\Entity\Register", inversedBy="registerCurrentData")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="register_id", referencedColumnName="id")
     * })
     */
    private $register;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_of_update", type="datetime", nullable=false)
     */
    private $timeOfUpdate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="get_to_process", type="boolean", nullable=false, options={"default"=true})
     */
    private $getToProcess;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="success_write", type="boolean", nullable=false, options={"default"=true})
     */
    private $successWrite;
    
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=30, nullable=true)
     */
    private $username;
    
    /**
     * Set value
     *
     * @param string $value
     *
     * @return RegisterWriteData
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set timeOfUpdate
     *
     * @param \DateTime $timeOfUpdate
     *
     * @return RegisterWriteData
     */
    public function setTimeOfUpdate($timeOfUpdate)
    {
        $this->timeOfUpdate = $timeOfUpdate;

        return $this;
    }

    /**
     * Get timeOfUpdate
     *
     * @return \DateTime
     */
    public function getTimeOfUpdate()
    {
        return $this->timeOfUpdate;
    }

    /**
     * Set register
     *
     * @param \BmsConfigurationBundle\Entity\Register $register
     *
     * @return RegisterWriteData
     */
    public function setRegister(\BmsConfigurationBundle\Entity\Register $register)
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
     * Set getToProcess
     *
     * @param boolean $getToProcess
     *
     * @return RegisterWriteData
     */
    public function setGetToProcess($getToProcess)
    {
        $this->getToProcess = $getToProcess;

        return $this;
    }

    /**
     * Get getToProcess
     *
     * @return boolean
     */
    public function getGetToProcess()
    {
        return $this->getToProcess;
    }

    /**
     * Set successWrite
     *
     * @param boolean $successWrite
     *
     * @return RegisterWriteData
     */
    public function setSuccessWrite($successWrite)
    {
        $this->successWrite = $successWrite;

        return $this;
    }

    /**
     * Get successWrite
     *
     * @return boolean
     */
    public function getSuccessWrite()
    {
        return $this->successWrite;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return RegisterWriteData
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
