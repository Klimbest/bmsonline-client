<?php

namespace VisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PanelImage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VisualizationBundle\Entity\Repository\PanelImageRepository")
 */
class PanelImage
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
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=50, nullable=false)
     */
    private $source;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tooltip", type="boolean", nullable=false, options={"default": false})
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
     * @var integer
     *
     * @ORM\Column(name="borderWidth", type="integer", nullable=true, options={"default"=1})
     */
    private $borderWidth;

    /**
     * @var string
     *
     * @ORM\Column(name="borderStyle", type="string", length=20, nullable=true, options={"default"="solid"})
     */
    private $borderStyle;

    /**
     * @var string
     *
     * @ORM\Column(name="borderColor", type="string", length=20, nullable=true, options={"default"="#000000"})
     */
    private $borderColor;

    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="panels_image")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    private $page;

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
        $this->name =  "pi_" . rand(999, 9999);
        $this->tooltip = false;
        $this->topPosition = 0;
        $this->leftPosition = 0;
        $this->width = 100;
        $this->height = 50;
        $this->zIndex = 5;
        $this->borderWidth = 0;
        $this->borderStyle = 'solid';
        $this->borderColor = "#000000";
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
     * @return PanelImage
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
     * Set source
     *
     * @param string $source
     *
     * @return PanelImage
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set tooltip
     *
     * @param boolean $tooltip
     *
     * @return PanelImage
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
     * @return PanelImage
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
     * @return PanelImage
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
     * @return PanelImage
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
     * @return PanelImage
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
     * @return PanelImage
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
     * Set borderWidth
     *
     * @param integer $borderWidth
     *
     * @return PanelImage
     */
    public function setBorderWidth($borderWidth)
    {
        $this->borderWidth = $borderWidth;

        return $this;
    }

    /**
     * Get borderWidth
     *
     * @return integer
     */
    public function getBorderWidth()
    {
        return $this->borderWidth;
    }

    /**
     * Set borderStyle
     *
     * @param string $borderStyle
     *
     * @return PanelImage
     */
    public function setBorderStyle($borderStyle)
    {
        $this->borderStyle = $borderStyle;

        return $this;
    }

    /**
     * Get borderStyle
     *
     * @return string
     */
    public function getBorderStyle()
    {
        return $this->borderStyle;
    }

    /**
     * Set borderColor
     *
     * @param string $borderColor
     *
     * @return PanelImage
     */
    public function setBorderColor($borderColor)
    {
        $this->borderColor = $borderColor;

        return $this;
    }

    /**
     * Get borderColor
     *
     * @return string
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }

    /**
     * Set page
     *
     * @param Page $page
     *
     * @return PanelImage
     */
    public function setPage(Page $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set eventLink
     *
     * @param EventLink $eventLink
     *
     * @return PanelImage
     */
    public function setEventLink(EventLink $eventLink = null)
    {
        $this->eventLink = $eventLink;

        return $this;
    }

    /**
     * Get eventLink
     *
     * @return EventLink
     */
    public function getEventLink()
    {
        return $this->eventLink;
    }
}
