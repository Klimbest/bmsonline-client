<?php

namespace VisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BmsConfigurationBundle\Entity\Register;

/**
 * InputNumber
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VisualizationBundle\Entity\Repository\InputNumberRepository")
 */
class InputNumber
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
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="inputs_number")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    private $page;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->name =  "in_" . rand(999, 9999);
        $this->tooltip = false;
        $this->topPosition = 0;
        $this->leftPosition = 0;
        $this->width = 100;
        $this->height = 50;
        $this->zIndex = 5;
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
     * @return InputNumber
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
     * Set page
     *
     * @param Page $page
     *
     * @return InputNumber
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
     * Set tooltip
     *
     * @param boolean $tooltip
     *
     * @return InputNumber
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
     * @return InputNumber
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
     * @return InputNumber
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
     * @return InputNumber
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
     * @return InputNumber
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
     * @return InputNumber
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
}
