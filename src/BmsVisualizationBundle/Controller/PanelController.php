<?php

namespace BmsVisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsVisualizationBundle\Entity\Panel;

class PanelController extends Controller {

    public function loadPanelDialogAction(Request $request) {
        if ($request->isXmlHttpRequest()) {


            $ret["template"] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:panelDialog.html.twig');
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function addPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            //get data
            $page_id = $request->request->get("page_id");
            $name = $request->request->get("name");
            $topPosition = $request->request->get("topPosition");
            $leftPosition = $request->request->get("leftPosition");
            $width = $request->request->get("width");
            $height = $request->request->get("height");
            $border = $request->request->get("border");
            $backgroundColor = $request->request->get("backgroundColor");
            $textAlign = $request->request->get("textAlign");
            $fontWeight = $request->request->get("fontWeight");
            $textDecoration = $request->request->get("textDecoration");
            $fontStyle = $request->request->get("fontStyle");
            $fontFamily = $request->request->get("fontFamily");
            $fontSize = $request->request->get("fontSize");
            $content_source = $request->request->get("content_source");
            $fontColor = $request->request->get("fontColor");
            $borderRadius = $request->request->get("borderRadius");
            $zIndex = $request->request->get("zIndex");


            if ($request->request->get("visibility") == "true") {
                $visibility = 1;
            } else {
                $visibility = 0;
            }

            $em = $this->getDoctrine()->getManager();

            $panel = new Panel();
            $page = $pageRepo->find($page_id);
            $panel->setPage($page)
                    ->setName($name)
                    ->setTopPosition($topPosition)
                    ->setLeftPosition($leftPosition)
                    ->setWidth($width)
                    ->setHeight($height)
                    ->setBorder($border)
                    ->setBackgroundColor($backgroundColor)
                    ->setTextAlign($textAlign)
                    ->setFontWeight($fontWeight)
                    ->setTextDecoration($textDecoration)
                    ->setFontStyle($fontStyle)
                    ->setFontFamily($fontFamily)
                    ->setFontSize($fontSize)
                    ->setContentSource($content_source)
                    ->setFontColor($fontColor)
                    ->setBorderRadius($borderRadius)
                    ->setZIndex($zIndex)
                    ->setVisibility($visibility);

            $em->persist($panel);
            $em->flush();

            $ret["template"] = $this->container->get('templating')->render('BmsVisualizationBundle::panel.html.twig', ['panel' => $panel]);
            $ret["resp"] = "Zrobione!";
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function movePanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel = $panelRepo->find($request->get("panel_id"));

            $height = $request->get("height");
            $width = $request->get("width");
            $topPosition = $request->get("topPosition");
            $leftPosition = $request->get("leftPosition");

            $panel->setHeight($height)
                    ->setWidth($width)
                    ->setLeftPosition($leftPosition)
                    ->setTopPosition($topPosition);

            $em->flush();

            return new JsonResponse();
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function copyPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $panel_id = $request->get("panel_id");

            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel = $panelRepo->find($panel_id);
            $newPanel = clone $panel;

            $newPanel->setTopPosition(0)->setLeftPosition(0);

            $em->persist($newPanel);
            $em->flush();

//            $ret["panel_id"] = $newPanel->getId();
//            $ret["type"] = $newPanel->getType();
            $ret["template"] = $this->container->get('templating')->render('BmsVisualizationBundle::panel.html.twig', ['panel' => $newPanel]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function deletePanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $panel_id = $request->get("panel_id");

            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel = $panelRepo->find($panel_id);

            $em->remove($panel);
            $em->flush();

            $em->getConnection()->exec("ALTER TABLE panel AUTO_INCREMENT = 1;");
            return new JsonResponse();
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
