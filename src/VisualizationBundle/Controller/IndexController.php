<?php

namespace VisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller
{
    /**
     * @Route("/", name="page_index")
     */
    public function indexAction()
    {
        $page = $this->getDoctrine()->getRepository('VisualizationBundle:Page')->findMainPage();

        return $this->redirectToRoute('page_show', ['id' => $page->getId()]);
    }
}
