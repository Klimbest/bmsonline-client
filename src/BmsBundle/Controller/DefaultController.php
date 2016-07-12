<?php

namespace BmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

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

            $page_id = $request->get("page_id");

            $ret['template'] = $this->container->get('templating')->render('BmsBundle:Pages:' . $page_id . '.html.twig');
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
            $bitRegisterRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:BitRegister');
            $termRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Term');
            $widgetBarRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:WidgetBar');
            $technicalInformationRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:TechnicalInformation');
            $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');

            $page_id = $request->get("page_id");
            
            $panelRegisters = $panelRepo->findVariablePanelsRegistersForPage($page_id);
            $ret['registers'] = $panelRegisters;

            $termRegisters = $termRepo->findRegisterTermsForPage($page_id);
            $ret['registers'] = array_unique(array_merge($ret['registers'], $termRegisters ), SORT_REGULAR);
            
            $widgetValueRegisters = $widgetBarRepo->findWidgetValueRegistersForPage($page_id);
            $ret['registers'] = array_unique(array_merge($ret['registers'], $widgetValueRegisters ), SORT_REGULAR);
            
            $widgetSetRegisters = $widgetBarRepo->findWidgetSetRegistersForPage($page_id);
            $ret['registers'] = array_unique(array_merge($ret['registers'], $widgetSetRegisters ), SORT_REGULAR);
            
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
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
