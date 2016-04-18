<?php

namespace BmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsVisualizationBundle\Entity\Term;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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
            
            $page_id = $request->get("page_id");
            $pages = $pageRepo->findAll();
            isset($page_id) ? $page = $pageRepo->find($page_id) : $page = $pageRepo->find(2);
            
            $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
            
            $terms = $termRepo->findAllAsArray();       
                        
            $ret["terms"] = $terms;
            $ret['template'] = $this->container->get('templating')->render('BmsBundle::page.html.twig', ['pages' => $pages, 'page' => $page]);
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
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');

            $page_id = $request->get("page_id");
            isset($page_id) ? $page = $pageRepo->find($page_id) : null;

            $panels = $panelRepo->findVariablePanelsForPage($page_id);
            $registers = array();
            foreach ($panels as $p) {
                $rid = $p->getContentSource();
                $register = $registerRepo->find($rid);
                $registers[$rid] = $register->getRegisterCurrentData()->getFixedValue();                    
            }

            $regsForTime = $registerRepo->findAll();
            $time = 0;
            foreach ($regsForTime as $rft) {
                $lastRead = date_timestamp_get($rft->getRegisterCurrentData()->getTimeOfUpdate());
                if ($lastRead > $time) {
                    $time = $lastRead;
                }
            }

            $ret["time_of_update"] = $time;
            $ret['registers'] = $registers;
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

//    public function makeCondition($condition_type, Term $term) {
//        $reg_val = $term->getRegister()->getRegisterCurrentData()->getFixedValue();
//        $condition_value = $term->getCondition()->getValue();
//        $id = $term->getId();
//        $t = array();
//
//        switch ($condition_type) {
//            case "==":
//                if ($reg_val == $condition_value) {
//                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
//                    $t[$id]["panel_id"] = $term->getPanel()->getId();
//                    $t[$id]["effect_type"] = $term->getEffect()->getType();
//                }
//                break;
//            case "!=":
//                if ($reg_val != $condition_value) {
//                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
//                    $t[$id]["panel_id"] = $term->getPanel()->getId();
//                    $t[$id]["effect_type"] = $term->getEffect()->getType();
//                }
//                break;
//            case ">":
//                if ($reg_val > $condition_value) {
//                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
//                    $t[$id]["panel_id"] = $term->getPanel()->getId();
//                    $t[$id]["effect_type"] = $term->getEffect()->getType();
//                }
//                break;
//            case "<":
//                if ($reg_val < $condition_value) {
//                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
//                    $t[$id]["panel_id"] = $term->getPanel()->getId();
//                    $t[$id]["effect_type"] = $term->getEffect()->getType();
//                }
//                break;
//            case ">=":
//                if ($reg_val >= $condition_value) {
//                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
//                    $t[$id]["panel_id"] = $term->getPanel()->getId();
//                    $t[$id]["effect_type"] = $term->getEffect()->getType();
//                }
//                break;
//            case "<=":
//                if ($reg_val <= $condition_value) {
//                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
//                    $t[$id]["panel_id"] = $term->getPanel()->getId();
//                    $t[$id]["effect_type"] = $term->getEffect()->getType();
//                }
//                break;
//        }
//        return $t;
//    }

}
