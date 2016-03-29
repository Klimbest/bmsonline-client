<?php

namespace BmsVisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

class PageController extends Controller {

    public function addPageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            //pobranie danych przesłanych żądaniem
            $height = $request->get("height");
            $width = $request->get("width");
            $name = $request->get("name");
            //dodanie nowej strony
            $page = new Page();
            $page->setHeight($height)
                    ->setWidth($width)
                    ->setName($name);

            $em->persist($page);
            $em->flush();

            $page_id = $page->getId();

            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $pages = $pageRepo->findAll();

            $template = $this->container->get('templating')->render('BmsVisualizationBundle::page.html.twig', ['pages' => $pages, 'page_id' => $page_id]);

            return new JsonResponse(array('ret' => $template));
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function deletePageAction(Request $request) {
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

    public function editPageAction(Request $request) {
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

    public function changePageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $page_id = $request->get("page_id");
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');

            $pages = $pageRepo->findAll();

            $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle::page.html.twig', ['pages' => $pages, 'page_id' => $page_id]);

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
