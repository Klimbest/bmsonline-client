<?php

namespace VisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BmsConfigurationBundle\Entity\Register;

/**
 * EventHideShow
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VisualizationBundle\Entity\Repository\EventChangeSourceRepository")
 */
class EventChangeSource {

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
     * @ORM\Column(name="panel_image_source", type="string", nullable=false)
     */
    private $panelImageSource;

    /**
     * @var float
     *
     * @ORM\Column(name="term_value", type="decimal", precision=9, scale=2, nullable=false)
     *
     */
    private $termValue;

    /**
     * @var string
     *
     * @ORM\Column(name="term_sign", type="string", nullable=false)
     */
    private $termSign;

    /**
     * @var Register
     *
     * @ORM\ManyToOne(targetEntity="\BmsConfigurationBundle\Entity\Register")
     * @ORM\JoinColumn(name="term_source", referencedColumnName="id", nullable=false)
     * })
     */
    private $termSource;

    /**
     * @ORM\ManyToOne(targetEntity="PanelImage", inversedBy="eventsChangeSource")
     * @ORM\JoinColumn(name="panel_image_id", referencedColumnName="id", nullable=true)
     */
    private $panelImage;



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
     * Set panelImageSource
     *
     * @param string $panelImageSource
     *
     * @return EventChangeSource
     */
    public function setPanelImageSource($panelImageSource)
    {
        $this->panelImageSource = $panelImageSource;

        return $this;
    }

    /**
     * Get panelImageSource
     *
     * @return string
     */
    public function getPanelImageSource()
    {
        return $this->panelImageSource;
    }

    /**
     * Set termValue
     *
     * @param string $termValue
     *
     * @return EventChangeSource
     */
    public function setTermValue($termValue)
    {
        $this->termValue = $termValue;

        return $this;
    }

    /**
     * Get termValue
     *
     * @return string
     */
    public function getTermValue()
    {
        return $this->termValue;
    }

    /**
     * Set termSign
     *
     * @param string $termSign
     *
     * @return EventChangeSource
     */
    public function setTermSign($termSign)
    {
        $this->termSign = $termSign;

        return $this;
    }

    /**
     * Get termSign
     *
     * @return string
     */
    public function getTermSign()
    {
        return $this->termSign;
    }

    /**
     * Set termSource
     *
     * @param \BmsConfigurationBundle\Entity\Register $termSource
     *
     * @return EventChangeSource
     */
    public function setTermSource(\BmsConfigurationBundle\Entity\Register $termSource)
    {
        $this->termSource = $termSource;

        return $this;
    }

    /**
     * Get termSource
     *
     * @return \BmsConfigurationBundle\Entity\Register
     */
    public function getTermSource()
    {
        return $this->termSource;
    }

    /**
     * Set panelImage
     *
     * @param \VisualizationBundle\Entity\PanelImage $panelImage
     *
     * @return EventChangeSource
     */
    public function setPanelImage(\VisualizationBundle\Entity\PanelImage $panelImage = null)
    {
        $this->panelImage = $panelImage;

        return $this;
    }

    /**
     * Get panelImage
     *
     * @return \VisualizationBundle\Entity\PanelImage
     */
    public function getPanelImage()
    {
        return $this->panelImage;
    }
}
