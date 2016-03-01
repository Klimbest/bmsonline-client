<?php

namespace BmsVisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panel
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Term {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="text")
     */
    private $name;

    /**
     * @var Panel
     * 
     * @ORM\ManyToOne(targetEntity="Panel")
     * @ORM\JoinColumn(name="panel_id", referencedColumnName="id", nullable=false)
     * 
     */
    private $panel;

    /**
     * @var Register
     * 
     * @ORM\ManyToOne(targetEntity="\BmsConfigurationBundle\Entity\Register")
     * @ORM\JoinColumn(name="register_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $register;

    /**
     * @var MyCondition
     * 
     * @ORM\ManyToOne(targetEntity="\BmsVisualizationBundle\Entity\MyCondition")
     * @ORM\JoinColumn(name="condition_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $condition;

    /**
     * @var Effect
     * 
     * @ORM\ManyToOne(targetEntity="\BmsVisualizationBundle\Entity\Effect")
     * @ORM\JoinColumn(name="effect_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $effect;

    

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
     * @return Term
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
     * Set panel
     *
     * @param \BmsVisualizationBundle\Entity\Panel $panel
     *
     * @return Term
     */
    public function setPanel(\BmsVisualizationBundle\Entity\Panel $panel)
    {
        $this->panel = $panel;

        return $this;
    }

    /**
     * Get panel
     *
     * @return \BmsVisualizationBundle\Entity\Panel
     */
    public function getPanel()
    {
        return $this->panel;
    }

    /**
     * Set register
     *
     * @param \BmsConfigurationBundle\Entity\Register $register
     *
     * @return Term
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
     * Set condition
     *
     * @param \BmsVisualizationBundle\Entity\MyCondition $condition
     *
     * @return Term
     */
    public function setCondition(\BmsVisualizationBundle\Entity\MyCondition $condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return \BmsVisualizationBundle\Entity\MyCondition
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Set effect
     *
     * @param \BmsVisualizationBundle\Entity\Effect $effect
     *
     * @return Term
     */
    public function setEffect(\BmsVisualizationBundle\Entity\Effect $effect)
    {
        $this->effect = $effect;

        return $this;
    }

    /**
     * Get effect
     *
     * @return \BmsVisualizationBundle\Entity\Effect
     */
    public function getEffect()
    {
        return $this->effect;
    }
}
