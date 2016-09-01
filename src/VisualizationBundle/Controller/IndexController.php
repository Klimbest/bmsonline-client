<?php

namespace VisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class IndexController extends Controller
{
    /**
     * @Route("/", name="visualization")
     */
    public function indexAction()
    {
        $page = $this->getDoctrine()->getRepository('VisualizationBundle:Page')->findMainPage();
        return $this->redirectToRoute('page_show', ['id' => $page->getId()]);
    }

    /**
     * @Route("/generate", name="page_generate")
     */
    public function generateAction()
    {
        $fs = new Filesystem();
        $finder = new Finder();
        $pages = $this->getDoctrine()->getRepository('VisualizationBundle:Page')->findAll();
        $finder->files()->in('../src/BmsBundle/Resources/views/Pages/');

        foreach ($finder as $file) {
            $fs->remove($file->getRealPath());
        }
        foreach ($pages as $page) {
            $content = $this->get('templating')->render('BmsBundle::page.html.twig', ['page' => $page, 'labels' => false]);

            $fs->dumpFile('../src/BmsBundle/Resources/views/Pages/' . $page->getId() . ".html.twig", $content);
        }
        $fs->remove($this->getParameter('kernel.cache_dir'));

        $page = $this->getDoctrine()->getRepository('VisualizationBundle:Page')->findMainPage();
        return $this->redirectToRoute('page_show', ['id' => $page->getId()]);
    }
}
