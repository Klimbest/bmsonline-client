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
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10, nullable=false)
     */
    private $type;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="visibility", type="boolean", nullable=false)
     */
    private $visibility;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="tooltip", type="boolean", nullable=false, options={"default"=false})
     */
    private $tooltip;    
            
    /**
     * @var integer
     *
     * @ORM\Column(name="topPosition", type="integer", nullable=false)
     */
    private $topPosition;

    /**
     * @var integer
     *
     * @ORM\Column(name="leftPosition", type="integer", nullable=false)
     */
    private $leftPosition;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer", nullable=false)
     */
    private $width;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer", nullable=false)
     */
    private $height;

    /**
     * @var integer
     * 
     * @ORM\Column(name="zIndex", type="integer", nullable=false)
     */
    private $zIndex;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="displayPrecision", type="integer", nullable=false)
     */
    private $displayPrecision;
    
    /**
     * @var string
     *
     * @ORM\Column(name="backgroundColor", type="string", length=30, nullable=false)
     */
    private $backgroundColor;
    
    /**
     * @var string
     *
     * @ORM\Column(name="border", type="string", length=20, nullable=true)
     */
    private $border;

    /**
     * @var string
     *
     * @ORM\Column(name="borderRadius", type="string", length=30, nullable=true)
     */
    private $borderRadius;
    
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
     * @ORM\Column(name="content_source", type="text", nullable=true)
     */
    private $contentSource;

    /**
     * @var string
     *
     * @ORM\Column(name="href", type="text", nullable=true)
     */
    private $href;
    
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
     * Set name
     *
     * @param string $name
     *
     * @return Panel
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
     * Set visibility
     *
     * @param boolean $visibility
     *
     * @return Panel
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return boolean
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set contentSource
     *
     * @param string $contentSource
     *
     * @return Panel
     */
    public function setContentSource($contentSource)
    {
        $this->contentSource = $contentSource;

        return $this;
    }

    /**
     * Get contentSource
     *
     * @return string
     */
    public function getContentSource()
    {
        return $this->contentSource;
    }

    /**
     * Set border
     *
     * @param string $border
     *
     * @return Panel
     */
    public function setBorder($border)
    {
        $this->border = $border;

        return $this;
    }

    /**
     * Get border
     *
     * @return string
     */
    public function getBorder()
    {
        return $this->border;
    }

    /**
     * Set displayPrecision
     *
     * @param integer $displayPrecision
     *
     * @return Panel
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
     * Set href
     *
     * @param string $href
     *
     * @return Panel
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Get href
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }
        
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->terms = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tooltip
     *
     * @param boolean $tooltip
     *
     * @return Panel
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
}
