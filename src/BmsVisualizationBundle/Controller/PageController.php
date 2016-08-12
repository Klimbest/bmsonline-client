<?php

namespace BmsVisualizationBundle\Controller;

use BmsVisualizationBundle\BmsVisualizationBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BmsVisualizationBundle\Entity\Page;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PageController extends Controller
{

    /**
     * @Route("/add_page", name="bms_visualization_add_page", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function addPageAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            //pobranie danych przesłanych żądaniem
            $height = $request->get("height");
            $width = $request->get("width");
            $name = $request->get("name");
            $backgroundColor = $request->get("backgroundColor");
            //dodanie nowej strony
            $page = new Page();
            $page->setHeight($height)
                ->setWidth($width)
                ->setName($name)
                ->setBackgroundColor($backgroundColor);

            $em->persist($page);
            $em->flush();

            $page_id = $page->getId();

            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $pages = $pageRepo->findAll();

            $ret['page'] = $this->get('templating')->render('BmsVisualizationBundle::page.html.twig', ['pages' => $pages, 'page_id' => $page_id]);

            $panels = $panelRepo->findPanelsForPage($page_id);
            $ret['panelList'] = $this->get('templating')->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/delete_page", name="bms_visualization_delete_page", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePageAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $page_id = $request->get("page_id");
            if ($page_id == 1) {
                $ret['result'] = "Nie można usunąć strony głównej";
            } else {
                $em = $this->getDoctrine()->getManager();
                $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
                $page = $pageRepo->find($page_id);
                $panels = $page->getPanels();
                foreach ($panels as $panel) {
                    $em->remove($panel);
                }
                $em->flush();
                $em->remove($page);
                $em->flush();
                $em->getConnection()->exec("ALTER TABLE panel AUTO_INCREMENT = 1;");
                $em->getConnection()->exec("ALTER TABLE page AUTO_INCREMENT = 1;");
                $ret['result'] = "Usunięto stronę: id = " . $page_id . ", wraz ze wszystkimi panelami na tej stronie.";
            }
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/edit_page", name="bms_visualization_edit_page", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function editPageAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $page_id = $request->get("page_id");
            $em = $this->getDoctrine()->getManager();
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $page = $pageRepo->find($page_id);

            $height = $request->get("height");
            $width = $request->get("width");
            $name = $request->get("name");

            $page->setHeight($height)
                ->setWidth($width)
                ->setName($name);
            $em->flush();
            $ret['page_id'] = $page_id;
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/change_page", name="bms_visualization_change_page", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function changePageAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $page_id = $request->get("page_id");
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $widgetBarRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:WidgetBar');
            $termRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Term');
            $pages = $pageRepo->findAll();
            $panels = $panelRepo->findPanelsForPage($request->get("page_id"));
            $widgets = $widgetBarRepo->findAll();
            $ret['registers'] = $panelRepo->findVariablePanelsRegistersForPage($page_id);
            $ret['registers'] = array_unique(array_merge($ret['registers'], $termRepo->findRegisterTermsForPage($page_id)), SORT_REGULAR);
            $ret['registers'] = array_unique(array_merge($ret['registers'], $widgetBarRepo->findWidgetValueRegistersForPage($page_id)), SORT_REGULAR);
            $ret['registers'] = array_unique(array_merge($ret['registers'], $widgetBarRepo->findWidgetSetRegistersForPage($page_id)), SORT_REGULAR);
            $ret['page'] = $this->get('templating')->render('BmsVisualizationBundle::page.html.twig', ['pages' => $pages, 'page_id' => $page_id, 'widgets' => $widgets]);
            $ret['panelList'] = $this->get('templating')->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/generate", name="bms_visualization_generate", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function generateAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $fs = new Filesystem();
            $finder = new Finder();
            $pages = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page')->findAll();
            $widgets = $this->getDoctrine()->getRepository('BmsVisualizationBundle:WidgetBar')->findAll();
            $finder->files()->in('../src/BmsBundle/Resources/views/Pages/');

            foreach ($finder as $file) {
                $fs->remove($file->getRealPath());
            }
            foreach ($pages as $page) {
                $content = $this->get('templating')->render('BmsBundle::page.html.twig', ['page' => $page, 'widgets' => $widgets]);

                $fs->dumpFile('../src/BmsBundle/Resources/views/Pages/' . $page->getId() . ".html.twig", $content);
            }
            $fs->remove($this->getParameter('kernel.cache_dir'));
            return new JsonResponse($fs);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
