<?php

namespace VisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use BmsConfigurationBundle\Entity\Register;

/**
 * PanelVariable
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VisualizationBundle\Entity\Repository\PanelVariableRepository")
 */
class PanelVariable
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
     * @var Register
     *
     * @ORM\ManyToOne(targetEntity="\BmsConfigurationBundle\Entity\Register")
     * @ORM\JoinColumn(name="source", referencedColumnName="id", nullable=true)
     * })
     */
    private $source;

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
     * @var integer
     *
     * @ORM\Column(name="displayPrecision", type="integer", nullable=true, options={"default"=2})
     */
    private $displayPrecision;

    /**
     * @var string
     *
     * @ORM\Column(name="backgroundColor", type="string", length=30, nullable=true, options={"default"="#FFFFFF"})
     */
    private $backgroundColor;

    /**
     * @var float
     *
     * @ORM\Column(name="backgroundOpacity", type="float", nullable=true, options={"default"=0})
     */
    private $backgroundOpacity;

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
     * @var integer
     *
     * @ORM\Column(name="borderRadiusLeftTop", type="integer", nullable=true, options={"default"=0})
     */
    private $borderRadiusLeftTop;

    /**
     * @var integer
     *
     * @ORM\Column(name="borderRadiusLeftBottom", type="integer", nullable=true, options={"default"=0})
     */
    private $borderRadiusLeftBottom;

    /**
     * @var integer
     *
     * @ORM\Column(name="borderRadiusRightTop", type="integer", nullable=true, options={"default"=0})
     */
    private $borderRadiusRightTop;

    /**
     * @var integer
     *
     * @ORM\Column(name="borderRadiusRightBottom", type="integer", nullable=true, options={"default"=0})
     */
    private $borderRadiusRightBottom;

    /**
     * @var string
     *
     * @ORM\Column(name="textAlign", type="string", length=15, nullable=true, options={"default"="left"})
     */
    private $textAlign;

    /**
     * @var string
     *
     * @ORM\Column(name="fontWeight", type="string", length=15, nullable=true, options={"default"="400"})
     */
    private $fontWeight;

    /**
     * @var string
     *
     * @ORM\Column(name="textDecoration", type="string", length=15, nullable=true, options={"default"="none"})
     */
    private $textDecoration;

    /**
     * @var string
     *
     * @ORM\Column(name="fontStyle", type="string", length=35, nullable=true, options={"default"="normal"})
     */
    private $fontStyle;

    /**
     * @var string
     *
     * @ORM\Column(name="fontFamily", type="string", length=15, nullable=true, options={"default"="Arial"})
     */
    private $fontFamily;

    /**
     * @var string
     *
     * @ORM\Column(name="fontColor", type="string", length=30, nullable=true, options={"default" : "#000000"})
     */
    private $fontColor;

    /**
     * @var integer
     *
     * @ORM\Column(name="fontSize", type="integer", nullable=true, options={"default"=14})
     */
    private $fontSize;

    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="panels_variable")
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
        $this->name =  "pv_" . rand(999, 9999);
        $this->tooltip = false;
        $this->topPosition = 0;
        $this->leftPosition = 0;
        $this->width = 100;
        $this->height = 50;
        $this->zIndex = 5;
        $this->displayPrecision = 2;
        $this->backgroundOpacity = 0;
        $this->borderWidth = 0;
        $this->borderStyle = 'solid';
        $this->borderColor = "#000000";
        $this->borderRadiusLeftBottom = 0;
        $this->borderRadiusLeftTop = 0;
        $this->borderRadiusRightBottom = 0;
        $this->borderRadiusRightTop = 0;
        $this->textAlign = 'left';
        $this->fontWeight = 'normal';
        $this->textDecoration = 'none';
        $this->fontStyle = 'normal';
        $this->fontFamily = 'Arial';
        $this->fontColor = '#000000';
        $this->fontSize = 14;
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
     * @return PanelVariable
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
     * @return PanelVariable
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
     * @return PanelVariable
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
     * @return PanelVariable
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
     * @return PanelVariable
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
     * @return PanelVariable
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
     * @return PanelVariable
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
     * Set displayPrecision
     *
     * @param integer $displayPrecision
     *
     * @return PanelVariable
     */
    public function setDisplayPrecision($displayPrecision)
    {
        $this->displayPrecision = $displayPrecision;

        return $this;
    }

    /**
     * Get displayPrecision
     *
     * @return integer
     */
    public function getDisplayPrecision()
    {
        return $this->displayPrecision;
    }

    /**
     * Set backgroundColor
     *
     * @param string $backgroundColor
     *
     * @return PanelVariable
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
     * Set borderWidth
     *
     * @param integer $borderWidth
     *
     * @return PanelVariable
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
     * @return PanelVariable
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
     * @return PanelVariable
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
     * Set borderRadiusLeftTop
     *
     * @param integer $borderRadiusLeftTop
     *
     * @return PanelVariable
     */
    public function setBorderRadiusLeftTop($borderRadiusLeftTop)
    {
        $this->borderRadiusLeftTop = $borderRadiusLeftTop;

        return $this;
    }

    /**
     * Get borderRadiusLeftTop
     *
     * @return integer
     */
    public function getBorderRadiusLeftTop()
    {
        return $this->borderRadiusLeftTop;
    }

    /**
     * Set borderRadiusLeftBottom
     *
     * @param integer $borderRadiusLeftBottom
     *
     * @return PanelVariable
     */
    public function setBorderRadiusLeftBottom($borderRadiusLeftBottom)
    {
        $this->borderRadiusLeftBottom = $borderRadiusLeftBottom;

        return $this;
    }

    /**
     * Get borderRadiusLeftBottom
     *
     * @return integer
     */
    public function getBorderRadiusLeftBottom()
    {
        return $this->borderRadiusLeftBottom;
    }

    /**
     * Set borderRadiusRightTop
     *
     * @param integer $borderRadiusRightTop
     *
     * @return PanelVariable
     */
    public function setBorderRadiusRightTop($borderRadiusRightTop)
    {
        $this->borderRadiusRightTop = $borderRadiusRightTop;

        return $this;
    }

    /**
     * Get borderRadiusRightTop
     *
     * @return integer
     */
    public function getBorderRadiusRightTop()
    {
        return $this->borderRadiusRightTop;
    }

    /**
     * Set borderRadiusRightBottom
     *
     * @param integer $borderRadiusRightBottom
     *
     * @return PanelVariable
     */
    public function setBorderRadiusRightBottom($borderRadiusRightBottom)
    {
        $this->borderRadiusRightBottom = $borderRadiusRightBottom;

        return $this;
    }

    /**
     * Get borderRadiusRightBottom
     *
     * @return integer
     */
    public function getBorderRadiusRightBottom()
    {
        return $this->borderRadiusRightBottom;
    }

    /**
     * Set textAlign
     *
     * @param string $textAlign
     *
     * @return PanelVariable
     */
    public function setTextAlign($textAlign)
    {
        $this->textAlign = $textAlign;

        return $this;
    }

    /**
     * Get textAlign
     *
     * @return string
     */
    public function getTextAlign()
    {
        return $this->textAlign;
    }

    /**
     * Set fontWeight
     *
     * @param string $fontWeight
     *
     * @return PanelVariable
     */
    public function setFontWeight($fontWeight)
    {
        $this->fontWeight = $fontWeight;

        return $this;
    }

    /**
     * Get fontWeight
     *
     * @return string
     */
    public function getFontWeight()
    {
        return $this->fontWeight;
    }

    /**
     * Set textDecoration
     *
     * @param string $textDecoration
     *
     * @return PanelVariable
     */
    public function setTextDecoration($textDecoration)
    {
        $this->textDecoration = $textDecoration;

        return $this;
    }

    /**
     * Get textDecoration
     *
     * @return string
     */
    public function getTextDecoration()
    {
        return $this->textDecoration;
    }

    /**
     * Set fontStyle
     *
     * @param string $fontStyle
     *
     * @return PanelVariable
     */
    public function setFontStyle($fontStyle)
    {
        $this->fontStyle = $fontStyle;

        return $this;
    }

    /**
     * Get fontStyle
     *
     * @return string
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    /**
     * Set fontFamily
     *
     * @param string $fontFamily
     *
     * @return PanelVariable
     */
    public function setFontFamily($fontFamily)
    {
        $this->fontFamily = $fontFamily;

        return $this;
    }

    /**
     * Get fontFamily
     *
     * @return string
     */
    public function getFontFamily()
    {
        return $this->fontFamily;
    }

    /**
     * Set fontColor
     *
     * @param string $fontColor
     *
     * @return PanelVariable
     */
    public function setFontColor($fontColor)
    {
        $this->fontColor = $fontColor;

        return $this;
    }

    /**
     * Get fontColor
     *
     * @return string
     */
    public function getFontColor()
    {
        return $this->fontColor;
    }

    /**
     * Set fontSize
     *
     * @param integer $fontSize
     *
     * @return PanelVariable
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;

        return $this;
    }

    /**
     * Get fontSize
     *
     * @return integer
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * Set page
     *
     * @param Page $page
     *
     * @return PanelVariable
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
     * Set backgroundOpacity
     *
     * @param float $backgroundOpacity
     *
     * @return PanelVariable
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
     * Set eventLink
     *
     * @param EventLink $eventLink
     *
     * @return PanelVariable
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

    /**
     * Set source
     *
     * @param Register $source
     *
     * @return PanelVariable
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
}
