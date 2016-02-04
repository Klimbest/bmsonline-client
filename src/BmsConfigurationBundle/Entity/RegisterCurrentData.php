<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegisterCurrentData
 *
 * @ORM\Table(name="register_current_data")
 * @ORM\Entity
 */
class RegisterCurrentData
{
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
     * @var decimal
     *
     * @ORM\Column(name="fixed_value", type="decimal", precision=9, scale=2, nullable=true)
     */
    private $fixedValue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_of_update", type="datetime", nullable=false)
     */
    private $timeOfUpdate;

    /**
     * @var \BmsConfigurationBundle\Entity\Register
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="BmsConfigurationBundle\Entity\Register", inversedBy="registerCurrentData")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="register_id", referencedColumnName="id")
     * })
     */
    private $register;



    /**
     * Set realValueHex
     *
     * @param string $realValueHex
     * @return RegisterCurrentData
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
     * @return RegisterCurrentData
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
     * @return RegisterCurrentData
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
     * Set timeOfUpdate
     *
     * @param \DateTime $timeOfUpdate
     * @return RegisterCurrentData
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
     * @return RegisterCurrentData
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
}
