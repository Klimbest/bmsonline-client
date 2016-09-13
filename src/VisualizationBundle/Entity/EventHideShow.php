<?php

namespace VisualizationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use BmsConfigurationBundle\Entity\Register;

/**
 * EventHideShow
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VisualizationBundle\Entity\Repository\EventHideShowRepository")
 */
class EventHideShow {

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
     * @ORM\Column(name="term", type="string", nullable=false)
     */
    private $term;

    /**
     * @var Register
     *
     * @ORM\ManyToOne(targetEntity="\BmsConfigurationBundle\Entity\Register")
     * @ORM\JoinColumn(name="source", referencedColumnName="id", nullable=false)
     * })
     */
    private $source;

    /**
     * @ORM\ManyToMany(targetEntity="PanelImage", mappedBy="eventsHideShow")
     */
    private $panelsImage;

    /**
     * @ORM\ManyToMany(targetEntity="PanelText", mappedBy="eventsHideShow")
     */
    private $panelsText;

    /**
     * @ORM\ManyToMany(targetEntity="PanelVariable", mappedBy="eventsHideShow")
     */
    private $panelsVariable;


    public function __construct() {
        $this->panelsImage = new ArrayCollection();
        $this->panelsText = new ArrayCollection();
        $this->panelsVariable = new ArrayCollection();
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
     * Set term
     *
     * @param string $term
     *
     * @return EventHideShow
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get term
     *
     * @return string
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set source
     *
     * @param Register $source
     *
     * @return EventHideShow
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

    /**
     * Add panelsImage
     *
     * @param \VisualizationBundle\Entity\PanelImage $panelsImage
     *
     * @return EventHideShow
     */
    public function addPanelsImage(\VisualizationBundle\Entity\PanelImage $panelsImage)
    {
        $this->panelsImage[] = $panelsImage;

        return $this;
    }

    /**
     * Remove panelsImage
     *
     * @param \VisualizationBundle\Entity\PanelImage $panelsImage
     */
    public function removePanelsImage(\VisualizationBundle\Entity\PanelImage $panelsImage)
    {
        $this->panelsImage->removeElement($panelsImage);
    }

    /**
     * Get panelsImage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPanelsImage()
    {
        return $this->panelsImage;
    }

    /**
     * Add panelsText
     *
     * @param \VisualizationBundle\Entity\PanelText $panelsText
     *
     * @return EventHideShow
     */
    public function addPanelsText(\VisualizationBundle\Entity\PanelText $panelsText)
    {
        $this->panelsText[] = $panelsText;

        return $this;
    }

    /**
     * Remove panelsText
     *
     * @param \VisualizationBundle\Entity\PanelText $panelsText
     */
    public function removePanelsText(\VisualizationBundle\Entity\PanelText $panelsText)
    {
        $this->panelsText->removeElement($panelsText);
    }

    /**
     * Get panelsText
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPanelsText()
    {
        return $this->panelsText;
    }

    /**
     * Add panelsVariable
     *
     * @param \VisualizationBundle\Entity\PanelVariable $panelsVariable
     *
     * @return EventHideShow
     */
    public function addPanelsVariable(\VisualizationBundle\Entity\PanelVariable $panelsVariable)
    {
        $this->panelsVariable[] = $panelsVariable;

        return $this;
    }

    /**
     * Remove panelsVariable
     *
     * @param \VisualizationBundle\Entity\PanelVariable $panelsVariable
     */
    public function removePanelsVariable(\VisualizationBundle\Entity\PanelVariable $panelsVariable)
    {
        $this->panelsVariable->removeElement($panelsVariable);
    }

    /**
     * Get panelsVariable
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPanelsVariable()
    {
        return $this->panelsVariable;
    }
}
