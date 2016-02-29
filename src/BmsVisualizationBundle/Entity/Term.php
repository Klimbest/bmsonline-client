<?php

namespace BmsVisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BmsVisualizationBundle\Entity\PanelRepository")
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
     * @ORM\Column(name="effect_condition", type="text", nullable=false)
     */
    private $effectCondition;
    
    /**
     * @var string
     *
     * @ORM\Column(name="effect_field", type="text", nullable=false)
     */
    private $effectField;
    
    /**
     * @var string
     *
     * @ORM\Column(name="effect_content", type="text", nullable=false)
     */
    private $effectContent;
    
    
    /**
     * @var Panel
     * 
     * @ORM\ManyToOne(targetEntity="Panel")
     * @ORM\JoinColumn(name="effect_panel_id", referencedColumnName="id")
     * 
     */
    private $effectPanel;
    
    /**
     * @var Register
     * 
     * @ORM\ManyToOne(targetEntity="\BmsConfigurationBundle\Entity\Register")
     * @ORM\JoinColumn(name="register_id", referencedColumnName="id")
     * })
     */
    private $register;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }


    /**
     * Set effectField
     *
     * @param string $effectField
     *
     * @return Term
     */
    public function setEffectField($effectField)
    {
        $this->effectField = $effectField;

        return $this;
    }

    /**
     * Get effectField
     *
     * @return string
     */
    public function getEffectField()
    {
        return $this->effectField;
    }

    /**
     * Set effectContent
     *
     * @param string $effectContent
     *
     * @return Term
     */
    public function setEffectContent($effectContent)
    {
        $this->effectContent = $effectContent;

        return $this;
    }

    /**
     * Get effectContent
     *
     * @return string
     */
    public function getEffectContent()
    {
        return $this->effectContent;
    }

    /**
     * Set effectPanel
     *
     * @param \BmsVisualizationBundle\Entity\Panel $effectPanel
     *
     * @return Term
     */
    public function setEffectPanel(\BmsVisualizationBundle\Entity\Panel $effectPanel = null)
    {
        $this->effectPanel = $effectPanel;

        return $this;
    }

    /**
     * Get effectPanel
     *
     * @return \BmsVisualizationBundle\Entity\Panel
     */
    public function getEffectPanel()
    {
        return $this->effectPanel;
    }

   

    /**
     * Set register
     *
     * @param \BmsConfigurationBundle\Entity\Register $register
     *
     * @return Term
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
     * Set effectCondition
     *
     * @param string $effectCondition
     *
     * @return Term
     */
    public function setEffectCondition($effectCondition)
    {
        $this->effectCondition = $effectCondition;

        return $this;
    }

    /**
     * Get effectCondition
     *
     * @return string
     */
    public function getEffectCondition()
    {
        return $this->effectCondition;
    }
}
