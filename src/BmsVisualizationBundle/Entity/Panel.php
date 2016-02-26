<?php

namespace BmsVisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BmsVisualizationBundle\Entity\PanelRepository")
 */
class Panel {

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
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="topPosition", type="integer")
     */
    private $topPosition;

    /**
     * @var integer
     *
     * @ORM\Column(name="leftPosition", type="integer")
     */
    private $leftPosition;

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
     * @var integer
     * 
     * @ORM\Column(name="zIndex", type="integer")
     */
    private $zIndex;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="borderWidth", type="integer", nullable=true)
     */
    private $borderWidth;

    /**
     * @var string
     *
     * @ORM\Column(name="borderStyle", type="string", length=20, nullable=true)
     */
    private $borderStyle;

    /**
     * @var string
     *
     * @ORM\Column(name="borderColor", type="string", length=30, nullable=true)
     */
    private $borderColor;

    /**
     * @var string
     *
     * @ORM\Column(name="borderRadius", type="string", length=30, nullable=true)
     */
    private $borderRadius;

    /**
     * @var string
     *
     * @ORM\Column(name="backgroundColor", type="string", length=30, nullable=true)
     */
    private $backgroundColor;

    /**
     * @var float
     *
     * @ORM\Column(name="opacity", type="float", nullable=true)
     */
    private $opacity;

    /**
     * @var string
     *
     * @ORM\Column(name="textAlign", type="string", length=15, nullable=true)
     */
    private $textAlign;

    /**
     * @var string
     *
     * @ORM\Column(name="fontWeight", type="string", length=15, nullable=true)
     */
    private $fontWeight;

    /**
     * @var string
     *
     * @ORM\Column(name="textDecoration", type="string", length=15, nullable=true)
     */
    private $textDecoration;

    /**
     * @var string
     *
     * @ORM\Column(name="fontStyle", type="string", length=35, nullable=true)
     */
    private $fontStyle;

    /**
     * @var string
     *
     * @ORM\Column(name="fontFamily", type="string", length=15, nullable=true)
     */
    private $fontFamily;

    /**
     * @var string
     *
     * @ORM\Column(name="fontColor", type="string", length=30, nullable=true)
     */
    private $fontColor;

    /**
     * @var integer
     *
     * @ORM\Column(name="fontSize", type="integer", nullable=true)
     */
    private $fontSize;

    
    
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var Page
     * 
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="panels")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     * })
     */
    private $page;
  
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set topPosition
     *
     * @param integer $topPosition
     * @return Panel
     */
    public function setTopPosition($topPosition) {
        $this->topPosition = $topPosition;

        return $this;
    }

    /**
     * Get topPosition
     *
     * @return integer 
     */
    public function getTopPosition() {
        return $this->topPosition;
    }

    /**
     * Set leftPosition
     *
     * @param integer $leftPosition
     * @return Panel
     */
    public function setLeftPosition($leftPosition) {
        $this->leftPosition = $leftPosition;

        return $this;
    }

    /**
     * Get leftPosition
     *
     * @return integer 
     */
    public function getLeftPosition() {
        return $this->leftPosition;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return Panel
     */
    public function setWidth($width) {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer 
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Panel
     */
    public function setHeight($height) {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * Set borderStyle
     *
     * @param string $borderStyle
     * @return Panel
     */
    public function setBorderStyle($borderStyle) {
        $this->borderStyle = $borderStyle;

        return $this;
    }

    /**
     * Get borderStyle
     *
     * @return string 
     */
    public function getBorderStyle() {
        return $this->borderStyle;
    }

    /**
     * Set borderColor
     *
     * @param string $borderColor
     * @return Panel
     */
    public function setBorderColor($borderColor) {
        $this->borderColor = $borderColor;

        return $this;
    }

    /**
     * Get borderColor
     *
     * @return string 
     */
    public function getBorderColor() {
        return $this->borderColor;
    }

    /**
     * Set backgroundColor
     *
     * @param string $backgroundColor
     * @return Panel
     */
    public function setBackgroundColor($backgroundColor) {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * Get backgroundColor
     *
     * @return string 
     */
    public function getBackgroundColor() {
        return $this->backgroundColor;
    }

    /**
     * Set opacity
     *
     * @param float $opacity
     * @return Panel
     */
    public function setOpacity($opacity) {
        $this->opacity = $opacity;

        return $this;
    }

    /**
     * Get opacity
     *
     * @return float 
     */
    public function getOpacity() {
        return $this->opacity;
    }

    /**
     * Set borderWidth
     *
     * @param integer $borderWidth
     * @return Panel
     */
    public function setBorderWidth($borderWidth) {
        $this->borderWidth = $borderWidth;

        return $this;
    }

    /**
     * Get borderWidth
     *
     * @return integer 
     */
    public function getBorderWidth() {
        return $this->borderWidth;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Panel
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set page
     *
     * @param \BmsVisualizationBundle\Entity\Page $page
     * @return Panel
     */
    public function setPage(\BmsVisualizationBundle\Entity\Page $page = null) {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \BmsVisualizationBundle\Entity\Page 
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Panel
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set textAlign
     *
     * @param string $textAlign
     * @return Panel
     */
    public function setTextAlign($textAlign) {
        $this->textAlign = $textAlign;

        return $this;
    }

    /**
     * Get textAlign
     *
     * @return string 
     */
    public function getTextAlign() {
        return $this->textAlign;
    }

    /**
     * Set fontWeight
     *
     * @param string $fontWeight
     * @return Panel
     */
    public function setFontWeight($fontWeight) {
        $this->fontWeight = $fontWeight;

        return $this;
    }

    /**
     * Get fontWeight
     *
     * @return string 
     */
    public function getFontWeight() {
        return $this->fontWeight;
    }

    /**
     * Set textDecoration
     *
     * @param string $textDecoration
     * @return Panel
     */
    public function setTextDecoration($textDecoration) {
        $this->textDecoration = $textDecoration;

        return $this;
    }

    /**
     * Get textDecoration
     *
     * @return string 
     */
    public function getTextDecoration() {
        return $this->textDecoration;
    }

    /**
     * Set fontStyle
     *
     * @param string $fontStyle
     * @return Panel
     */
    public function setFontStyle($fontStyle) {
        $this->fontStyle = $fontStyle;

        return $this;
    }

    /**
     * Get fontStyle
     *
     * @return string 
     */
    public function getFontStyle() {
        return $this->fontStyle;
    }

    /**
     * Set fontFamily
     *
     * @param string $fontFamily
     * @return Panel
     */
    public function setFontFamily($fontFamily) {
        $this->fontFamily = $fontFamily;

        return $this;
    }

    /**
     * Get fontFamily
     *
     * @return string 
     */
    public function getFontFamily() {
        return $this->fontFamily;
    }

    /**
     * Set fontSize
     *
     * @param integer $fontSize
     * @return Panel
     */
    public function setFontSize($fontSize) {
        $this->fontSize = $fontSize;

        return $this;
    }

    /**
     * Get fontSize
     *
     * @return integer 
     */
    public function getFontSize() {
        return $this->fontSize;
    }

    /**
     * Set fontColor
     *
     * @param string $fontColor
     * @return Panel
     */
    public function setFontColor($fontColor) {
        $this->fontColor = $fontColor;

        return $this;
    }

    /**
     * Get fontColor
     *
     * @return string 
     */
    public function getFontColor() {
        return $this->fontColor;
    }

    /**
     * Set borderRadius
     *
     * @param string $borderRadius
     * @return Panel
     */
    public function setBorderRadius($borderRadius) {
        $this->borderRadius = $borderRadius;

        return $this;
    }

    /**
     * Get borderRadius
     *
     * @return string 
     */
    public function getBorderRadius() {
        return $this->borderRadius;
    }


    /**
     * Set zIndex
     *
     * @param integer $zIndex
     * @return Panel
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
     * Constructor
     */
    public function __construct()
    {
        $this->terms = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
}
