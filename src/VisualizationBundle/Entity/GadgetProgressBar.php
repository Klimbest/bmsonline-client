<?php

namespace VisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BmsConfigurationBundle\Entity\Register;

/**
 * GadgetProgressBar
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VisualizationBundle\Entity\Repository\GadgetProgressBarRepository")
 */
class GadgetProgressBar
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
     * @var integer
     *
     * @ORM\Column(name="range_min", type="decimal", precision=9, scale=2, nullable=false)
     */
    private $rangeMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="range_max", type="decimal", precision=9, scale=2, nullable=false)
     */
    private $rangeMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="optimum_min", type="decimal", precision=9, scale=2, nullable=false)
     */
    private $optimumMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="optimum_max", type="decimal", precision=9, scale=2, nullable=false)
     */
    private $optimumMax;

    /**
     * @var string
     *
     * @ORM\Column(name="color1", type="string", length=50, nullable=false)
     */
    private $color1;

    /**
     * @var string
     *
     * @ORM\Column(name="color2", type="string", length=50, nullable=false)
     */
    private $color2;

    /**
     * @var string
     *
     * @ORM\Column(name="color3", type="string", length=50, nullable=false)
     */
    private $color3;

    /**
     * @var Register
     *
     * @ORM\ManyToOne(targetEntity="\BmsConfigurationBundle\Entity\Register")
     * @ORM\JoinColumn(name="set_register_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $setRegisterId;

    /**
     * @var Register
     *
     * @ORM\ManyToOne(targetEntity="\BmsConfigurationBundle\Entity\Register")
     * @ORM\JoinColumn(name="value_register_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $valueRegisterId;

    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="gadgets_progress_bar")
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
        $this->name =  "gpb_" . rand(999, 9999);
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
     * @return GadgetProgressBar
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
     * Set rangeMin
     *
     * @param string $rangeMin
     *
     * @return GadgetProgressBar
     */
    public function setRangeMin($rangeMin)
    {
        $this->rangeMin = $rangeMin;

        return $this;
    }

    /**
     * Get rangeMin
     *
     * @return string
     */
    public function getRangeMin()
    {
        return $this->rangeMin;
    }

    /**
     * Set rangeMax
     *
     * @param string $rangeMax
     *
     * @return GadgetProgressBar
     */
    public function setRangeMax($rangeMax)
    {
        $this->rangeMax = $rangeMax;

        return $this;
    }

    /**
     * Get rangeMax
     *
     * @return string
     */
    public function getRangeMax()
    {
        return $this->rangeMax;
    }

    /**
     * Set optimumMin
     *
     * @param string $optimumMin
     *
     * @return GadgetProgressBar
     */
    public function setOptimumMin($optimumMin)
    {
        $this->optimumMin = $optimumMin;

        return $this;
    }

    /**
     * Get optimumMin
     *
     * @return string
     */
    public function getOptimumMin()
    {
        return $this->optimumMin;
    }

    /**
     * Set optimumMax
     *
     * @param string $optimumMax
     *
     * @return GadgetProgressBar
     */
    public function setOptimumMax($optimumMax)
    {
        $this->optimumMax = $optimumMax;

        return $this;
    }

    /**
     * Get optimumMax
     *
     * @return string
     */
    public function getOptimumMax()
    {
        return $this->optimumMax;
    }

    /**
     * Set color1
     *
     * @param string $color1
     *
     * @return GadgetProgressBar
     */
    public function setColor1($color1)
    {
        $this->color1 = $color1;

        return $this;
    }

    /**
     * Get color1
     *
     * @return string
     */
    public function getColor1()
    {
        return $this->color1;
    }

    /**
     * Set color2
     *
     * @param string $color2
     *
     * @return GadgetProgressBar
     */
    public function setColor2($color2)
    {
        $this->color2 = $color2;

        return $this;
    }

    /**
     * Get color2
     *
     * @return string
     */
    public function getColor2()
    {
        return $this->color2;
    }

    /**
     * Set color3
     *
     * @param string $color3
     *
     * @return GadgetProgressBar
     */
    public function setColor3($color3)
    {
        $this->color3 = $color3;

        return $this;
    }

    /**
     * Get color3
     *
     * @return string
     */
    public function getColor3()
    {
        return $this->color3;
    }

    /**
     * Set setRegisterId
     *
     * @param Register $setRegisterId
     *
     * @return GadgetProgressBar
     */
    public function setSetRegisterId(Register $setRegisterId = null)
    {
        $this->setRegisterId = $setRegisterId;

        return $this;
    }

    /**
     * Get setRegisterId
     *
     * @return Register
     */
    public function getSetRegisterId()
    {
        return $this->setRegisterId;
    }

    /**
     * Set valueRegisterId
     *
     * @param Register $valueRegisterId
     *
     * @return GadgetProgressBar
     */
    public function setValueRegisterId(Register $valueRegisterId)
    {
        $this->valueRegisterId = $valueRegisterId;

        return $this;
    }

    /**
     * Get valueRegisterId
     *
     * @return Register
     */
    public function getValueRegisterId()
    {
        return $this->valueRegisterId;
    }

    /**
     * Set page
     *
     * @param Page $page
     *
     * @return GadgetProgressBar
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
     * @return GadgetProgressBar
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
     * @return GadgetProgressBar
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
     * @return GadgetProgressBar
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
     * @return GadgetProgressBar
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
     * @return GadgetProgressBar
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
     * @return GadgetProgressBar
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
     * Set eventLink
     *
     * @param EventLink $eventLink
     *
     * @return GadgetProgressBar
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
