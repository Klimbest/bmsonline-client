<?php

namespace BmsVisualizationBundle\Controller;

use BmsConfigurationBundle\BmsConfigurationBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsVisualizationBundle\Entity\Panel;
use BmsVisualizationBundle\Entity\WidgetBar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BmsVisualizationBundle\Form\PanelType;

class PanelController extends Controller
{

    /**
     * @Route("/add_panel", name="bms_visualization_add_panel", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function addPanelAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $options = array();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $template = $this->get('templating');
            $panel = new Panel();
            $panel->setName($panelRepo->getNewPanelName());

            $form = $this->createForm(PanelType::class, $panel, array(
                'action' => $this->generateUrl('bms_visualization_add_panel'),
                'method' => 'POST'
            ));
            $options['form'] = $form->createView();
            $options['pages'] = $pageRepo->findAll();
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $page_id = (int)$request->request->get('panel')['page_id'];
                $page = $pageRepo->find($page_id);
                $panel->setPage($page);
                $em->persist($panel);
                $em->flush();
                if ($panel->getType() === 'variable') {
                    $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
                    $ret["registers"] = ["id" => $panel->getId(),
                        "name" => $panel->getContentSource()];
                    $ret["registers"] = array_merge($ret["registers"], $registerRepo->getFixedValueByRegisterName($panel->getContentSource()));
                } else {
                    $ret["registers"] = [];
                }
                $ret["panel"] = $template->render('BmsVisualizationBundle::panel.html.twig', ['panel' => $panel]);
                $panels = $panelRepo->findPanelsForPage($page->getId());
                $ret['panelList'] = $template->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);
            } else {
                $ret["template"] = $template->render('BmsVisualizationBundle:dialog:panelManager.html.twig', $options);
            }
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/edit_panel", name="bms_visualization_edit_panel", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function editPanelAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $options = array();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $panel_id = (int)$request->request->get('panel_id');
            $panel = $panelRepo->find($panel_id);
            $form = $this->createForm(PanelType::class, $panel, array(
                'action' => $this->generateUrl('bms_visualization_edit_panel'),
                'method' => 'POST'
            ));
            $options['form'] = $form->createView();
            $options['pages'] = $pageRepo->findAll();
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($panel);
                $em->flush();
                if ($panel->getType() === 'variable') {
                    $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
                    $ret["registers"] = ["id" => $panel->getId(),
                        "name" => $panel->getContentSource()];
                    $ret["registers"] = array_merge($ret["registers"], $registerRepo->getFixedValueByRegisterName($panel->getContentSource()));
                } else {
                    $ret["registers"] = [];
                }
                $ret["edit"] = 1;
                $ret["panel_id"] = $panel->getId();
                $ret["panel"] = $this->get('templating')->render('BmsVisualizationBundle::panel.html.twig', ['panel' => $panel]);
                return new JsonResponse($ret);
            } else {
                $ret["panel_type"] = $panel->getType();
                $ret["panel_id"] = $panel->getId();
                $ret["template"] = $this->get('templating')->render('BmsVisualizationBundle:dialog:panelManager.html.twig', $options);
                return new JsonResponse($ret);
            }
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/move_panel", name="bms_visualization_move_panel", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function movePanelAction(Request $request)
    {
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
            $ret['panelList'] = $this->get('templating')->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/copy_panel", name="bms_visualization_copy_panel", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function copyPanelAction(Request $request)
    {
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
            $options['newId'] = (int)($newId + 1);

            $ret["dialog"] = $this->get('templating')->render('BmsVisualizationBundle:dialog:panelManager.html.twig', $options);

            $ret["panel_id"] = $newPanel->getId();
            $ret["template"] = $this->get('templating')->render('BmsVisualizationBundle::panel.html.twig', ['panel' => $newPanel]);

            $panels = $panelRepo->findPanelsForPage($panel->getPage()->getId());
            $ret['panelList'] = $this->get('templating')->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/delete_panel", name="bms_visualization_delete_panel", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePanelAction(Request $request)
    {
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
            if ($panel->getType() == "widget") {
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
            $ret['panelList'] = $this->get('templating')->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
