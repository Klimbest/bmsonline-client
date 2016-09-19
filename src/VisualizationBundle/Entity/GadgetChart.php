<?php

namespace VisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BmsConfigurationBundle\Entity\Register;

/**
 * GadgetClock
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VisualizationBundle\Entity\Repository\GadgetChartRepository")
 */
class GadgetChart
{

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
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tooltip", type="boolean", options={"default": false})
     */
    private $tooltip;

    /**
     * @var integer
     *
     * @ORM\Column(name="topPosition", type="integer", nullable=false, options={"default":0})
     */
    private $topPosition;

    /**
     * @var integer
     *
     * @ORM\Column(name="leftPosition", type="integer", nullable=false, options={"default":"0"})
     */
    private $leftPosition;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer", nullable=false, options={"default" : 100})
     */
    private $width;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer", nullable=false, options={"default"=100})
     */
    private $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="zIndex", type="integer", nullable=false, options={"default"=5})
     */
    private $zIndex;

    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="gadgets_clock")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    private $page;

    /**
     * @var Register
     *
     * @ORM\ManyToOne(targetEntity="\BmsConfigurationBundle\Entity\Register")
     * @ORM\JoinColumn(name="source", referencedColumnName="id", nullable=false)
     * })
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="serie_color", type="string", length=50, nullable=false)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="background_color", type="string", length=50, nullable=false)
     */
    private $backgroundColor;

    /**
     * @var float
     *
     * @ORM\Column(name="background_opacity", type="float", nullable=true, options={"default"=0})
     */
    private $backgroundOpacity;

    /**
     * @var EventLink
     *
     * @ORM\ManyToOne(targetEntity="EventLink")
     * @ORM\JoinColumn(name="event_link_id", referencedColumnName="id", nullable=true)
     */
    private $eventLink;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->name =  "gct_" . rand(999, 9999);
        $this->tooltip = false;
        $this->topPosition = 0;
        $this->leftPosition = 0;
        $this->width = 100;
        $this->height = 50;
        $this->zIndex = 5;
        $this->backgroundOpacity = 0;
        $this->color = '#FF0000';
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
     * @return GadgetChart
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
     * Set tooltip
     *
     * @param boolean $tooltip
     *
     * @return GadgetChart
     */
    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    /**
     * Get tooltip
     *
     * @return boolean
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * Set topPosition
     *
     * @param integer $topPosition
     *
     * @return GadgetChart
     */
    public function setTopPosition($topPosition)
    {
        $this->topPosition = $topPosition;

        return $this;
    }

    /**
     * Get topPosition
     *
     * @return integer
     */
    public function getTopPosition()
    {
        return $this->topPosition;
    }

    /**
     * Set leftPosition
     *
     * @param integer $leftPosition
     *
     * @return GadgetChart
     */
    public function setLeftPosition($leftPosition)
    {
        $this->leftPosition = $leftPosition;

        return $this;
    }

    /**
     * Get leftPosition
     *
     * @return integer
     */
    public function getLeftPosition()
    {
        return $this->leftPosition;
    }

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return GadgetChart
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
     * @return GadgetChart
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
     * Set zIndex
     *
     * @param integer $zIndex
     *
     * @return GadgetChart
     */
    public function setZIndex($zIndex)
    {
        $this->zIndex = $zIndex;

        return $this;
    }

    /**
     * Get zIndex
     *
     * @return integer
     */
    public function getZIndex()
    {
        return $this->zIndex;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return GadgetChart
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set backgroundColor
     *
     * @param string $backgroundColor
     *
     * @return GadgetChart
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
     * Set backgroundOpacity
     *
     * @param float $backgroundOpacity
     *
     * @return GadgetChart
     */
    public function setBackgroundOpacity($backgroundOpacity)
    {
        $this->backgroundOpacity = $backgroundOpacity;

        return $this;
    }

    /**
     * Get backgroundOpacity
     *
     * @return float
     */
    public function getBackgroundOpacity()
    {
        return $this->backgroundOpacity;
    }

    /**
     * Set page
     *
     * @param \VisualizationBundle\Entity\Page $page
     *
     * @return GadgetChart
     */
    public function setPage(\VisualizationBundle\Entity\Page $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \VisualizationBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set source
     *
     * @param \BmsConfigurationBundle\Entity\Register $source
     *
     * @return GadgetChart
     */
    public function setSource(\BmsConfigurationBundle\Entity\Register $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \BmsConfigurationBundle\Entity\Register
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set eventLink
     *
     * @param \VisualizationBundle\Entity\EventLink $eventLink
     *
     * @return GadgetChart
     */
    public function setEventLink(\VisualizationBundle\Entity\EventLink $eventLink = null)
    {
        $this->eventLink = $eventLink;

        return $this;
    }

    /**
     * Get eventLink
     *
     * @return \VisualizationBundle\Entity\EventLink
     */
    public function getEventLink()
    {
        return $this->eventLink;
    }
}
