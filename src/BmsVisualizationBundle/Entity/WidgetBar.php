<?php

namespace BmsVisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WidgetBar
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BmsVisualizationBundle\Entity\WidgetBarRepository")
 */
class WidgetBar {

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
     * @var integer
     *
     * @ORM\Column(name="range_min", type="integer", nullable=false)
     */
    private $rangeMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="range_max", type="integer", nullable=false)
     */
    private $rangeMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="optimum_min", type="integer", nullable=false)
     */
    private $optimumMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="optimum_max", type="integer", nullable=false)
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
     * @return WidgetBar
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
     * @param integer $rangeMin
     *
     * @return WidgetBar
     */
    public function setRangeMin($rangeMin)
    {
        $this->rangeMin = $rangeMin;

        return $this;
    }

    /**
     * Get rangeMin
     *
     * @return integer
     */
    public function getRangeMin()
    {
        return $this->rangeMin;
    }

    /**
     * Set rangeMax
     *
     * @param integer $rangeMax
     *
     * @return WidgetBar
     */
    public function setRangeMax($rangeMax)
    {
        $this->rangeMax = $rangeMax;

        return $this;
    }

    /**
     * Get rangeMax
     *
     * @return integer
     */
    public function getRangeMax()
    {
        return $this->rangeMax;
    }

    /**
     * Set optimumMin
     *
     * @param integer $optimumMin
     *
     * @return WidgetBar
     */
    public function setOptimumMin($optimumMin)
    {
        $this->optimumMin = $optimumMin;

        return $this;
    }

    /**
     * Get optimumMin
     *
     * @return integer
     */
    public function getOptimumMin()
    {
        return $this->optimumMin;
    }

    /**
     * Set optimumMax
     *
     * @param integer $optimumMax
     *
     * @return WidgetBar
     */
    public function setOptimumMax($optimumMax)
    {
        $this->optimumMax = $optimumMax;

        return $this;
    }

    /**
     * Get optimumMax
     *
     * @return integer
     */
    public function getOptimumMax()
    {
        return $this->optimumMax;
    }

    /**
     * Set color1
     *
     * @param integer $color1
     *
     * @return WidgetBar
     */
    public function setColor1($color1)
    {
        $this->color1 = $color1;

        return $this;
    }

    /**
     * Get color1
     *
     * @return integer
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
     * @return WidgetBar
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
     * @return WidgetBar
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
     * @param \BmsConfigurationBundle\Entity\Register $setRegisterId
     *
     * @return WidgetBar
     */
    public function setSetRegisterId(\BmsConfigurationBundle\Entity\Register $setRegisterId)
    {
        $this->setRegisterId = $setRegisterId;

        return $this;
    }

    /**
     * Get setRegisterId
     *
     * @return \BmsConfigurationBundle\Entity\Register
     */
    public function getSetRegisterId()
    {
        return $this->setRegisterId;
    }

    /**
     * Set valueRegisterId
     *
     * @param \BmsConfigurationBundle\Entity\Register $valueRegisterId
     *
     * @return WidgetBar
     */
    public function setValueRegisterId(\BmsConfigurationBundle\Entity\Register $valueRegisterId)
    {
        $this->valueRegisterId = $valueRegisterId;

        return $this;
    }

    /**
     * Get valueRegisterId
     *
     * @return \BmsConfigurationBundle\Entity\Register
     */
    public function getValueRegisterId()
    {
        return $this->valueRegisterId;
    }
}
