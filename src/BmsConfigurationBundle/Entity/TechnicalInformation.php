<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TechnicalInformation
 *
 * @ORM\Table(name="technical_information")
 * @ORM\Entity
 */
class TechnicalInformation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="dataToSync", type="boolean")
     */
    private $dataToSync;
    

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
     * Set dataToSync
     *
     * @param boolean $dataToSync
     *
     * @return TechnicalInformation
     */
    public function setDataToSync($dataToSync)
    {
        $this->dataToSync = $dataToSync;

        return $this;
    }

    /**
     * Get dataToSync
     *
     * @return boolean
     */
    public function getDataToSync()
    {
        return $this->dataToSync;
    }
}
