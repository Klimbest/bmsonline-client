<?php

namespace BmsVisualizationBundle\Entity;

/**
 * PanelRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PanelRepository extends \Doctrine\ORM\EntityRepository
{
    public function findPanelsForPage($page_id){
        return $this->getEntityManager()
                ->createQuery('SELECT p FROM BmsVisualizationBundle:Panel AS p WHERE p.page = '.$page_id)
                ->getResult();
    }
}
