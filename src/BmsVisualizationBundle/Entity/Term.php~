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
     * @ORM\Column(name="condition", type="text", nullable=false)
     */
    private $condition;
    
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
     * @ORM\OneToOne(targetEntity="Panel")
     * @ORM\JoinColumn(name="effect_panel_id", referencedColumnName="id")
     * 
     */
    private $effectPanel;
    
    /**
     * @var Panel
     * 
     * @ORM\ManyToOne(targetEntity="Panel", inversedBy="terms")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="panel_id", referencedColumnName="id")
     * })
     */
    private $panel;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }


    /**
     * Set condition
     *
     * @param string $condition
     *
     * @return Term
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
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
     * Set panel
     *
     * @param \BmsVisualizationBundle\Entity\Panel $panel
     *
     * @return Term
     */
    public function setPanel(\BmsVisualizationBundle\Entity\Panel $panel = null)
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
}
