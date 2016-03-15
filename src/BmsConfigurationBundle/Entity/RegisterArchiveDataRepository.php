<?php

namespace BmsConfigurationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RegisterArchiveDataRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegisterArchiveDataRepository extends EntityRepository {

    public function getArchiveData($dateFrom, $registerAddres) {
        return $this->getEntityManager()
                        ->createQuery(
                                'SELECT rad.timeOfInsert, rad.fixedValue '
                                . 'FROM BmsConfigurationBundle:RegisterArchiveData AS rad '
                                . 'WHERE rad.registerAddress = ' . $registerAddres
                                . ' AND rad.timeOfInsert > ' . $dateFrom
                        )
                        ->getResult();
    }

}
