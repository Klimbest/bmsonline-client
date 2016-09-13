<?php

namespace VisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use BmsConfigurationBundle\Entity\Register;

/**
 * EventHideShow
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VisualizationBundle\Entity\Repository\EventHideShowRepository")
 */
class EventHideShow {

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
     * @ORM\Column(name="term", type="string", nullable=false)
     */
    private $term;

    /**
     * @var Register
     *
     * @ORM\ManyToOne(targetEntity="\BmsConfigurationBundle\Entity\Register")
     * @ORM\JoinColumn(name="source", referencedColumnName="id", nullable=false)
     * })
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity="PanelImage", inversedBy="eventsHideShow")
     * @ORM\JoinColumn(name="panel_image_id", referencedColumnName="id", nullable=true)
     */
    private $panelImage;

    /**
     * @ORM\ManyToOne(targetEntity="PanelText", inversedBy="eventsHideShow")
     * @ORM\JoinColumn(name="panel_text_id", referencedColumnName="id", nullable=true)
     */
    private $panelText;

    /**
     * @ORM\ManyToOne(targetEntity="PanelVariable", inversedBy="eventsHideShow")
     * @ORM\JoinColumn(name="panel_variable_id", referencedColumnName="id", nullable=true)
     */
    private $panelVariable;


    public function __construct() {
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
     * Set term
     *
     * @param string $term
     *
     * @return EventHideShow
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get term
     *
     * @return string
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set source
     *
     * @param Register $source
     *
     * @return EventHideShow
     */
    public function setSource(Register $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return Register
     */
    public function getSource()
    {
        return $this->source;
    }





    /**
     * Set panelImage
     *
     * @param \VisualizationBundle\Entity\PanelImage $panelImage
     *
     * @return EventHideShow
     */
    public function setPanelImage(\VisualizationBundle\Entity\PanelImage $panelImage = null)
    {
        $this->panelImage = $panelImage;

        return $this;
    }

    /**
     * Get panelImage
     *
     * @return \VisualizationBundle\Entity\PanelImage
     */
    public function getPanelImage()
    {
        return $this->panelImage;
    }

    /**
     * Set panelText
     *
     * @param \VisualizationBundle\Entity\PanelText $panelText
     *
     * @return EventHideShow
     */
    public function setPanelText(\VisualizationBundle\Entity\PanelText $panelText = null)
    {
        $this->panelText = $panelText;

        return $this;
    }

    /**
     * Get panelText
     *
     * @return \VisualizationBundle\Entity\PanelText
     */
    public function getPanelText()
    {
        return $this->panelText;
    }

    /**
     * Set panelVariable
     *
     * @param \VisualizationBundle\Entity\PanelVariable $panelVariable
     *
     * @return EventHideShow
     */
    public function setPanelVariable(\VisualizationBundle\Entity\PanelVariable $panelVariable = null)
    {
        $this->panelVariable = $panelVariable;

        return $this;
    }

    /**
     * Get panelVariable
     *
     * @return \VisualizationBundle\Entity\PanelVariable
     */
    public function getPanelVariable()
    {
        return $this->panelVariable;
    }
}
