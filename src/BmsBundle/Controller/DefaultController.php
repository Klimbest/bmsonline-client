<?php

namespace BmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsVisualizationBundle\Entity\Term;

class DefaultController extends Controller {

    public function bmsIndexAction() {


        return $this->render('BmsBundle::index.html.twig');
    }

    public function ajaxChangePageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $page_id = $request->get("page_id");
            $pages = $pageRepo->findAll();
            isset($page_id) ? $page = $pageRepo->find($page_id) : $page = $pageRepo->find(2);

            $ret['template'] = $this->container->get('templating')->render('BmsBundle::page.html.twig', ['pages' => $pages, 'page' => $page]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function ajaxRefreshPageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $termRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Term');

            $page_id = $request->get("page_id");
            isset($page_id) ? $page = $pageRepo->find($page_id) : null;

            $vid = $qb->select('p.content')->from('BmsVisualizationBundle:Panel', 'p')->where("p.type = 'variable'")->getQuery()->getResult();

            $registers = array();
            $t = array();
            foreach ($vid as $id) {
                $register = $registerRepo->find((int) $id['content']);
                $registers[$register->getId()] = $register->getRegisterCurrentData()->getFixedValue();
            }
            $terms = $termRepo->findAll();

            foreach ($terms as $term) {
                $pid = $term->getPanel()->getId();
                $panel = $panelRepo->findOneById($pid);

                if ($panel->getPage()->getId() == $page_id) {
                    $condition_type = $term->getCondition()->getType();
                    $t = array_merge($t, $this->makeCondition($condition_type, $term));
                }
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
            $ret["terms"] = $t;
            $ret['registers'] = $registers;
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function makeCondition($condition_type, Term $term) {
        $reg_val = $term->getRegister()->getRegisterCurrentData()->getFixedValue();
        $condition_value = $term->getCondition()->getValue();
        $id = $term->getId();
        $t = array();

        switch ($condition_type) {
            case "==":
                if ($reg_val == $condition_value) {
                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
                    $t[$id]["panel_id"] = $term->getPanel()->getId();
                    $t[$id]["effect_type"] = $term->getEffect()->getType();
                }
                break;
            case "!=":
                if ($reg_val != $condition_value) {
                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
                    $t[$id]["panel_id"] = $term->getPanel()->getId();
                    $t[$id]["effect_type"] = $term->getEffect()->getType();
                }
                break;
            case ">":
                if ($reg_val > $condition_value) {
                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
                    $t[$id]["panel_id"] = $term->getPanel()->getId();
                    $t[$id]["effect_type"] = $term->getEffect()->getType();
                }
                break;
            case "<":
                if ($reg_val < $condition_value) {
                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
                    $t[$id]["panel_id"] = $term->getPanel()->getId();
                    $t[$id]["effect_type"] = $term->getEffect()->getType();
                }
                break;
            case ">=":
                if ($reg_val >= $condition_value) {
                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
                    $t[$id]["panel_id"] = $term->getPanel()->getId();
                    $t[$id]["effect_type"] = $term->getEffect()->getType();
                }
                break;
            case "<=":
                if ($reg_val <= $condition_value) {
                    $t[$id]["effect_content"] = $term->getEffect()->getContent();
                    $t[$id]["panel_id"] = $term->getPanel()->getId();
                    $t[$id]["effect_type"] = $term->getEffect()->getType();
                }
                break;
        }
        return $t;
    }

}
