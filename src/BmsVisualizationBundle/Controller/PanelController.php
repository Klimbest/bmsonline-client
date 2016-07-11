<?php

namespace BmsVisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsVisualizationBundle\Entity\Panel;
use BmsVisualizationBundle\Entity\WidgetBar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BmsVisualizationBundle\Form\PanelType;

class PanelController extends Controller {

    /**
     * @Route("/load_panel_dialog", name="bms_visualization_load_panel_dialog", options={"expose"=true})
     */
    public function loadPanelDialogAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $options = array();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel_id = $request->get("panel_id");
            isset($panel_id) ? $panel = $panelRepo->find($panel_id) : $panel = new Panel();            
            
            $form = $this->createForm(PanelType::class, $panel, array(
                'action' => $this->generateUrl('bms_visualization_load_panel_dialog'),
                'method' => 'POST'
            ));
            $options['form'] = $form->createView();

            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($panel);
                $em->flush();

                return new JsonResponse(array('message' => 'Success!'), 200);
            }

            $ret["template"] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:panelDialog.html.twig', $options);

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }


    /**
     * @Route("/move_panel", name="bms_visualization_move_panel", options={"expose"=true})
     */
    public function movePanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel = $panelRepo->find($request->get("panel_id"));

            $height = $request->get("height");
            $width = $request->get("width");
            $topPosition = $request->get("topPosition");
            $leftPosition = $request->get("leftPosition");
            $zIndex = $request->get("zIndex");

            $panel->setHeight($height)
                    ->setWidth($width)
                    ->setLeftPosition($leftPosition)
                    ->setTopPosition($topPosition)
                    ->setZIndex($zIndex);

            $em->flush();

            $panels = $panelRepo->findPanelsForPage($panel->getPage()->getId());
            $ret['panelList'] = $this->container->get('templating')->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/copy_panel", name="bms_visualization_copy_panel", options={"expose"=true})
     */
    public function copyPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $options = array();

            $panel_id = $request->get("panel_id");

            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel = $panelRepo->find($panel_id);

            if ($panel->getType() === "variable") {
                $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
                $bitRegisterRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:BitRegister');
                $rid = $panel->getContentSource();
                if (substr($rid, 0, 3) == "bit") {
                    $register = $bitRegisterRepo->find(substr($rid, 3));
                    $r["value"] = $register->getBitValue();
                } else {
                    $register = $registerRepo->find($rid);
                    $r["value"] = $register->getRegisterCurrentData()->getFixedValue();
                }

                $options['register'] = $register;
                $r["name"] = $register->getName();
                $ret["register"] = $r;
            }

            $newPanel = clone $panel;

            $newPanel->setTopPosition(0)->setLeftPosition(0);

            $em->persist($newPanel);
            $em->flush();

            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $pages = $pageRepo->findAll();
            $options['pages'] = $pages;

            $lastPanel = $panelRepo->findLastPanel();
            $newId = $lastPanel->getId();
            $options['newId'] = (int) ($newId + 1);

            $ret["dialog"] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:panelDialog.html.twig', $options);

            $ret["panel_id"] = $newPanel->getId();
            $ret["template"] = $this->container->get('templating')->render('BmsVisualizationBundle::panel.html.twig', ['panel' => $newPanel]);

            $panels = $panelRepo->findPanelsForPage($panel->getPage()->getId());
            $ret['panelList'] = $this->container->get('templating')->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/delete_panel", name="bms_visualization_delete_panel", options={"expose"=true})
     */
    public function deletePanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $panel_id = $request->get("panel_id");

            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $termRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Term');

            $panel = $panelRepo->find($panel_id);
            $terms = $termRepo->findAllForPanelAsObject($panel_id);
            foreach ($terms as $term) {
                $em->remove($term);
            }
            if($panel->getType() == "widget"){
                $widgetBarRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:WidgetBar');
                $widgetBar_id = $panel->getContentSource();
                $widgetBar = $widgetBarRepo->find($widgetBar_id);
                $em->remove($widgetBar);
            }
            $em->remove($panel);
            $em->flush();

            $em->getConnection()->exec("ALTER TABLE panel AUTO_INCREMENT = 1;");
            $em->getConnection()->exec("ALTER TABLE term AUTO_INCREMENT = 1;");

            $panels = $panelRepo->findPanelsForPage($panel->getPage()->getId());
            $ret['panelList'] = $this->container->get('templating')->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
