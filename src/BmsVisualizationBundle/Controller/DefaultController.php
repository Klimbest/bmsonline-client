<?php

namespace BmsVisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller {

    /**
     * @Route("/", name="bms_visualization_index")
     */
    public function bmsVisualizationIndexAction() {
        return $this->render('BmsVisualizationBundle::index.html.twig');
    }

}
