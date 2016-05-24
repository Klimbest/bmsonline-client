<?php

namespace BmsAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller {

    /**
     * @Route("/", name="bms_admin")
     */
    public function indexAction() {
        return $this->render('BmsAdminBundle:Default:index.html.twig');
    }

}
