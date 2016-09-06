<?php

namespace VisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Panel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VisualizationBundle\Entity\Repository\PanelTextRepository")
 */
class PanelText
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
     * @ORM\Column(name="source", type="text", nullable=true)
     */
    private $source;

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
     * @ORM\Column(name="height", type="integer", nullable=true, options={"default"=100})
     */
    private $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="zIndex", type="integer", nullable=true, options={"default"=5})
     */
    private $zIndex;

    /**
     * @var string
     *
     * @ORM\Column(name="backgroundColor", type="string", length=30, nullable=true, options={"default"="#FFFFFF"})
     */
    private $backgroundColor;

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
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="panels_text")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    private $page;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->name =  "pt_" . rand(999, 9999);
        $this->topPosition = 0;
        $this->leftPosition = 0;
        $this->width = 100;
        $this->height = 50;
        $this->zIndex = 5;
        $this->backgroundColor = "#FFFFFF";
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
     * @return PanelText
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
     * @return PanelText
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
     * Set topPosition
     *
     * @param integer $topPosition
     *
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * Set backgroundColor
     *
     * @param string $backgroundColor
     *
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @return PanelText
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
     * @param \VisualizationBundle\Entity\Page $page
     *
     * @return PanelText
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
}
