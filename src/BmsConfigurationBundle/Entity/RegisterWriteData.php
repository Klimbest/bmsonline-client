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
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=4, nullable=true)
     */
    private $value;

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
     * @var \DateTime
     *
     * @ORM\Column(name="time_of_update", type="datetime", nullable=false)
     */
    private $timeOfUpdate;

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
}
