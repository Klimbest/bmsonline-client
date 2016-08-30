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
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="inputs_number")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    private $page;

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
     * @param \VisualizationBundle\Entity\Page $page
     *
     * @return InputNumber
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
