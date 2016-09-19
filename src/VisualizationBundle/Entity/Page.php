<?php

namespace VisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VisualizationBundle\Entity\Repository\PageRepository")
 */
class Page {

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer")
     */
    private $width;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer")
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="backgroundColor", type="string", length=20)
     */
    private $backgroundColor;

    /**
     * @var GadgetClock
     * 
     * @ORM\OneToMany(targetEntity="GadgetClock", mappedBy="page")
     */
    private $gadgets_clock;

    /**
     * @var GadgetChart
     *
     * @ORM\OneToMany(targetEntity="GadgetChart", mappedBy="page")
     */
    private $gadgets_chart;

    /**
     * @var GadgetProgressBar
     *
     * @ORM\OneToMany(targetEntity="GadgetProgressBar", mappedBy="page")
     */
    private $gadgets_progress_bar;

    /**
     * @var InputButton
     *
     * @ORM\OneToMany(targetEntity="InputButton", mappedBy="page")
     */
    private $inputs_button;

    /**
     * @var InputNumber
     *
     * @ORM\OneToMany(targetEntity="InputNumber", mappedBy="page")
     */
    private $inputs_number;

    /**
     * @var InputRange
     *
     * @ORM\OneToMany(targetEntity="InputRange", mappedBy="page")
     */
    private $inputs_range;

    /**
     * @var PanelImage
     *
     * @ORM\OneToMany(targetEntity="PanelImage", mappedBy="page")
     */
    private $panels_image;

    /**
     * @var PanelText
     *
     * @ORM\OneToMany(targetEntity="PanelText", mappedBy="page")
     */
    private $panels_text;

    /**
     * @var PanelVariable
     *
     * @ORM\OneToMany(targetEntity="PanelVariable", mappedBy="page")
     */
    private $panels_variable;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->width = 1200;
        $this->height = 700;
        $this->backgroundColor = '#FFFFFF';
        $this->gadgets_clock = new ArrayCollection();
        $this->gadgets_chart = new ArrayCollection();
        $this->gadgets_progress_bar = new ArrayCollection();
        $this->inputs_button = new ArrayCollection();
        $this->inputs_number = new ArrayCollection();
        $this->inputs_range = new ArrayCollection();
        $this->panels_image = new ArrayCollection();
        $this->panels_text = new ArrayCollection();
        $this->panels_variable = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
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
     * @return Page
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
     * Set width
     *
     * @param integer $width
     *
     * @return Page
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Page
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set backgroundColor
     *
     * @param string $backgroundColor
     *
     * @return Page
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * Get backgroundColor
     *
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * Add gadgetsClock
     *
     * @param \VisualizationBundle\Entity\GadgetClock $gadgetsClock
     *
     * @return Page
     */
    public function addGadgetsClock(\VisualizationBundle\Entity\GadgetClock $gadgetsClock)
    {
        $this->gadgets_clock[] = $gadgetsClock;

        return $this;
    }

    /**
     * Remove gadgetsClock
     *
     * @param \VisualizationBundle\Entity\GadgetClock $gadgetsClock
     */
    public function removeGadgetsClock(\VisualizationBundle\Entity\GadgetClock $gadgetsClock)
    {
        $this->gadgets_clock->removeElement($gadgetsClock);
    }

    /**
     * Get gadgetsClock
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGadgetsClock()
    {
        return $this->gadgets_clock;
    }

    /**
     * Add gadgetsProgressBar
     *
     * @param \VisualizationBundle\Entity\GadgetProgressBar $gadgetsProgressBar
     *
     * @return Page
     */
    public function addGadgetsProgressBar(\VisualizationBundle\Entity\GadgetProgressBar $gadgetsProgressBar)
    {
        $this->gadgets_progress_bar[] = $gadgetsProgressBar;

        return $this;
    }

    /**
     * Remove gadgetsProgressBar
     *
     * @param \VisualizationBundle\Entity\GadgetProgressBar $gadgetsProgressBar
     */
    public function removeGadgetsProgressBar(\VisualizationBundle\Entity\GadgetProgressBar $gadgetsProgressBar)
    {
        $this->gadgets_progress_bar->removeElement($gadgetsProgressBar);
    }

    /**
     * Get gadgetsProgressBar
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGadgetsProgressBar()
    {
        return $this->gadgets_progress_bar;
    }

    /**
     * Add inputsButton
     *
     * @param \VisualizationBundle\Entity\InputButton $inputsButton
     *
     * @return Page
     */
    public function addInputsButton(\VisualizationBundle\Entity\InputButton $inputsButton)
    {
        $this->inputs_button[] = $inputsButton;

        return $this;
    }

    /**
     * Remove inputsButton
     *
     * @param \VisualizationBundle\Entity\InputButton $inputsButton
     */
    public function removeInputsButton(\VisualizationBundle\Entity\InputButton $inputsButton)
    {
        $this->inputs_button->removeElement($inputsButton);
    }

    /**
     * Get inputsButton
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInputsButton()
    {
        return $this->inputs_button;
    }

    /**
     * Add inputsNumber
     *
     * @param \VisualizationBundle\Entity\InputNumber $inputsNumber
     *
     * @return Page
     */
    public function addInputsNumber(\VisualizationBundle\Entity\InputNumber $inputsNumber)
    {
        $this->inputs_number[] = $inputsNumber;

        return $this;
    }

    /**
     * Remove inputsNumber
     *
     * @param \VisualizationBundle\Entity\InputNumber $inputsNumber
     */
    public function removeInputsNumber(\VisualizationBundle\Entity\InputNumber $inputsNumber)
    {
        $this->inputs_number->removeElement($inputsNumber);
    }

    /**
     * Get inputsNumber
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInputsNumber()
    {
        return $this->inputs_number;
    }

    /**
     * Add inputsRange
     *
     * @param \VisualizationBundle\Entity\InputRange $inputsRange
     *
     * @return Page
     */
    public function addInputsRange(\VisualizationBundle\Entity\InputRange $inputsRange)
    {
        $this->inputs_range[] = $inputsRange;

        return $this;
    }

    /**
     * Remove inputsRange
     *
     * @param \VisualizationBundle\Entity\InputRange $inputsRange
     */
    public function removeInputsRange(\VisualizationBundle\Entity\InputRange $inputsRange)
    {
        $this->inputs_range->removeElement($inputsRange);
    }

    /**
     * Get inputsRange
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInputsRange()
    {
        return $this->inputs_range;
    }

    /**
     * Add panelsImage
     *
     * @param \VisualizationBundle\Entity\PanelImage $panelsImage
     *
     * @return Page
     */
    public function addPanelsImage(\VisualizationBundle\Entity\PanelImage $panelsImage)
    {
        $this->panels_image[] = $panelsImage;

        return $this;
    }

    /**
     * Remove panelsImage
     *
     * @param \VisualizationBundle\Entity\PanelImage $panelsImage
     */
    public function removePanelsImage(\VisualizationBundle\Entity\PanelImage $panelsImage)
    {
        $this->panels_image->removeElement($panelsImage);
    }

    /**
     * Get panelsImage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPanelsImage()
    {
        return $this->panels_image;
    }

    /**
     * Add panelsText
     *
     * @param \VisualizationBundle\Entity\PanelText $panelsText
     *
     * @return Page
     */
    public function addPanelsText(\VisualizationBundle\Entity\PanelText $panelsText)
    {
        $this->panels_text[] = $panelsText;

        return $this;
    }

    /**
     * Remove panelsText
     *
     * @param \VisualizationBundle\Entity\PanelText $panelsText
     */
    public function removePanelsText(\VisualizationBundle\Entity\PanelText $panelsText)
    {
        $this->panels_text->removeElement($panelsText);
    }

    /**
     * Get panelsText
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPanelsText()
    {
        return $this->panels_text;
    }

    /**
     * Add panelsVariable
     *
     * @param \VisualizationBundle\Entity\PanelVariable $panelsVariable
     *
     * @return Page
     */
    public function addPanelsVariable(\VisualizationBundle\Entity\PanelVariable $panelsVariable)
    {
        $this->panels_variable[] = $panelsVariable;

        return $this;
    }

    /**
     * Remove panelsVariable
     *
     * @param \VisualizationBundle\Entity\PanelVariable $panelsVariable
     */
    public function removePanelsVariable(\VisualizationBundle\Entity\PanelVariable $panelsVariable)
    {
        $this->panels_variable->removeElement($panelsVariable);
    }

    /**
     * Get panelsVariable
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPanelsVariable()
    {
        return $this->panels_variable;
    }

    /**
     * Add gadgetsChart
     *
     * @param \VisualizationBundle\Entity\GadgetChart $gadgetsChart
     *
     * @return Page
     */
    public function addGadgetsChart(\VisualizationBundle\Entity\GadgetChart $gadgetsChart)
    {
        $this->gadgets_chart[] = $gadgetsChart;

        return $this;
    }

    /**
     * Remove gadgetsChart
     *
     * @param \VisualizationBundle\Entity\GadgetChart $gadgetsChart
     */
    public function removeGadgetsChart(\VisualizationBundle\Entity\GadgetChart $gadgetsChart)
    {
        $this->gadgets_chart->removeElement($gadgetsChart);
    }

    /**
     * Get gadgetsChart
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGadgetsChart()
    {
        return $this->gadgets_chart;
    }
}
