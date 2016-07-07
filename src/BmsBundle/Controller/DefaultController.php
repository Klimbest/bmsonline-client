<?php

namespace BmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller {

    /**
     * @Route("/", name="bms_index")
     */
    public function bmsIndexAction() {

        return $this->render('BmsBundle::index.html.twig');
    }

    /**
     * @Route("/bms_change_page", name="bms_change_page", options={"expose"=true})
     */
    public function ajaxChangePageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $termRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Term');
            $widgetBarRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:WidgetBar');

            $page_id = $request->get("page_id");
            $pages = $pageRepo->findAll();
            isset($page_id) ? $page = $pageRepo->find($page_id) : $page = $pageRepo->find(2);

            $terms = $termRepo->findAllAsArray();

            $widgets = $widgetBarRepo->findAll();
            $ret["terms"] = $terms;
            $ret['template'] = $this->container->get('templating')->render('BmsBundle::page.html.twig', ['pages' => $pages, 'page' => $page, 'terms' => $terms, 'widgets' => $widgets]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/bms_refresh_page", name="bms_refresh_page", options={"expose"=true})
     */
    public function ajaxRefreshPageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $bitRegisterRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:BitRegister');
            $termRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Term');
            $widgetBarRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:WidgetBar');
            $technicalInformationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:TechnicalInformation');
            $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');

            $page_id = $request->get("page_id");
            //get all panels for page
            $panels = $panelRepo->findVariablePanelsForPage($page_id);
            foreach ($panels as $p) {
                $rid = $p->getContentSource();
                if(substr($rid, 0, 3) == "bit"){
                    $register = $bitRegisterRepo->find(substr($rid, 3));
                    $registers[$rid] = $register->getBitValue();
                }else{
                    $register = $registerRepo->find($rid);
                    $registers[$rid] = $register->getRegisterCurrentData()->getFixedValue();
                }
            }
            //get all terms
            $terms = $termRepo->findAll();
            foreach ($terms as $t) {
                $register = $t->getRegister();
                $registers[$register->getId()] = $register->getRegisterCurrentData()->getFixedValue();
            }
            //get all bar widgets
            $widgets = $widgetBarRepo->findAll();
            foreach ($widgets as $w) {
                if ($w->getSetRegisterId() != null) {
                    $register = $w->getSetRegisterId();
                    $registers[$register->getId()] = $register->getRegisterCurrentData()->getFixedValue();
                }
                $register = $w->getValueRegisterId();
                $registers[$register->getId()] = $register->getRegisterCurrentData()->getFixedValue();
            }
            $devicesStatus = $technicalInformationRepo->getDevicesStatus();

            foreach ($devicesStatus as &$ds) {
                $devices_id = explode("_", $ds['name']);
                $device = $deviceRepo->find((int) $devices_id[1]);
                $ds['name'] = $device->getName();
            }

            //get last hello from RPi      
            $time = $technicalInformationRepo->getRpiStatus();

            $ret['devicesStatus'] = $devicesStatus;
            $time ? $ret['state'] = $time[0]["time"]->getTimestamp() : $ret['state'] = null;
            $registers ? $ret['registers'] = $registers : $ret['registers'] = null;
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
