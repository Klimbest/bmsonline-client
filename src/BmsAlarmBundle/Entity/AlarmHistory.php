<?php

namespace BmsAlarmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AlarmHistory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BmsAlarmBundle\Entity\AlarmHistoryRepository")
 */
class AlarmHistory {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_of_occure", type="datetime", nullable=false)
     */
    private $timeOfOccure;

    /**
     * @var \BmsConfigurationBundle\Entity\Register
     *
     * @ORM\ManyToOne(targetEntity="BmsConfigurationBundle\Entity\Register")
     * @ORM\JoinColumn(name="register_id", referencedColumnName="id")
     * 
     */
    private $register;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="confirmed", type="boolean", nullable=false, options={"default"=false})
     */
    private $confirmed;    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="value", type="integer", nullable=true)
     */
    private $value;    


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
     * Set timeOfOccure
     *
     * @param \DateTime $timeOfOccure
     *
     * @return AlarmHistory
     */
    public function setTimeOfOccure($timeOfOccure)
    {
        $this->timeOfOccure = $timeOfOccure;

        return $this;
    }

    /**
     * Get timeOfOccure
     *
     * @return \DateTime
     */
    public function getTimeOfOccure()
    {
        return $this->timeOfOccure;
    }

    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     *
     * @return AlarmHistory
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set register
     *
     * @param \BmsConfigurationBundle\Entity\Register $register
     *
     * @return AlarmHistory
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
     * Set value
     *
     * @param integer $value
     *
     * @return AlarmHistory
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }
}
