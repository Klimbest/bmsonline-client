<?php

namespace BmsVisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity
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
     * @var Panel 
     * 
     * @ORM\OneToMany(targetEntity="Panel", mappedBy="page")
     */
    private $panels;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Page
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return Page
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
     * @return Page
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
     * Constructor
     */
    public function __construct()
    {
        $this->panels = new ArrayCollection();
    }

    /**
     * Add panels
     *
     * @param \BmsVisualizationBundle\Entity\Panel $panels
     * @return Page
     */
    public function addPanel(\BmsVisualizationBundle\Entity\Panel $panels)
    {
        $this->panels[] = $panels;

        return $this;
    }

    /**
     * Remove panels
     *
     * @param \BmsVisualizationBundle\Entity\Panel $panels
     */
    public function removePanel(\BmsVisualizationBundle\Entity\Panel $panels)
    {
        $this->panels->removeElement($panels);
    }

    /**
     * Get panels
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPanels()
    {
        return $this->panels;
    }
}
