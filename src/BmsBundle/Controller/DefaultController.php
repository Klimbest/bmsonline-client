<?php

namespace BmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller {

    public function bmsIndexAction() {

        
        return $this->render('BmsBundle::index.html.twig');
    }

    public function ajaxChangePageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $page_id = $request->get("page_id");
            isset($page_id) ? $page = $pageRepo->find($page_id) : $page = $pageRepo->find(2);

            $ret['template'] = $this->container->get('templating')->render('BmsBundle::page.html.twig', ['page' => $page]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function ajaxRefreshPageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');

            $page_id = $request->get("page_id");
            isset($page_id) ? $page = $pageRepo->find($page_id) : $page = $pageRepo->find(2);

            $vid = $qb->select('p.content')->from('BmsVisualizationBundle:Panel', 'p')->where("p.type = 'variable'")->getQuery()->getResult();
            
            $registers = array();
            foreach($vid as $id){
                $register = $registerRepo->find((int)$id['content']);
                $registers[$register->getId()] = $register->getRegisterCurrentData()->getFixedValue();
            }
            $ret['registers'] = $registers;
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}
