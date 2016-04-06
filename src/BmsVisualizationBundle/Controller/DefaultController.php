<?php

namespace BmsVisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function bmsVisualizationIndexAction() {
        return $this->render('BmsVisualizationBundle::index.html.twig');
    }

}
