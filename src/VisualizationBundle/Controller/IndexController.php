<?php

namespace VisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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

    /**
     * @Route("/move", name="element_move", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function moveElementAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $type = $request->get("element_type");
            $element_id = $request->get("element_id");

            $repository = $this->getDoctrine()->getRepository('VisualizationBundle:' . $type);
            $element = $repository->find($element_id);

            $height = $request->get("height");
            $width = $request->get("width");
            $topPosition = $request->get("topPosition");
            $leftPosition = $request->get("leftPosition");
            $zIndex = $request->get("zIndex");

            $element->setHeight($height)
                ->setWidth($width)
                ->setLeftPosition($leftPosition)
                ->setTopPosition($topPosition)
                ->setZIndex($zIndex);

            $em->flush();
            return new JsonResponse();
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
