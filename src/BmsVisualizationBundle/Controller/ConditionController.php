<?php

namespace BmsVisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
use BmsVisualizationBundle\Entity\Term;

class ConditionController extends Controller {

    public function createDialogAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            
            $registers = $registerRepo->findAll();
            $devices = $deviceRepo->findAll();
            $pages = $pageRepo->findAll();
            $panels = $panelRepo->findAll();
            
            $images = $this->getImages();
            $options = [
                'registers' => $registers,
                'devices' => $devices,
                'panels' => $panels,
                'pages' => $pages
            ];
            $options = array_merge($options, $images);
            $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:condition.html.twig', $options);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function createAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            
            $register_id = $request->get('register_id');
            $condition = $request->get('condition');
            $effect_field = $request->get('effect_field');
            $effect_content = $request->get('effect_content');
            $effect_panel_id = $request->get('effect_panel_id');
           
            $register = $registerRepo->findOneById($register_id);
            $panel = $panelRepo->findOneById($effect_panel_id);
            
            $term = new Term();
            $term->setRegister($register)
                    ->setEffectCondition($condition)
                    ->setEffectField($effect_field)
                    ->setEffectContent($effect_content)
                    ->setEffectPanel($panel);
            
            $em->persist($term);
            $em->flush();       
            $ret = "Zrobione!";
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }
    
    public function getImages(){
        $finder = new Finder();

        $finder->directories()->in($this->container->getParameter('kernel.root_dir') . '/../web/images/');
        $images = array();
        $sizeOfImage = array();
        foreach ($finder as $dir) {
            $finder2 = new Finder();
            $dirDet = explode("\\", $dir->getRelativePathname());
            switch (sizeof($dirDet)) {
                case 1 :
                    !isset($images[$dirDet[0]]) ? $images[$dirDet[0]] = array() : null;
                    $finder2->depth('== 0')->files()->in($this->container->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                    foreach ($finder2 as $file) {
                        $fn = $file->getFilename();
                        $images[$dirDet[0]][$fn] = $fn;
                        $sizeOfImage[$fn] = round($file->getSize()/1024);
                    }
                    break;
                case 2 :
                    !isset($images[$dirDet[0]][$dirDet[1]]) ? $images[$dirDet[0]][$dirDet[1]] = array() : null;
                    $finder2->depth('== 0')->files()->in($this->container->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                    foreach ($finder2 as $file) {
                        $fn = $file->getFilename();
                        $images[$dirDet[0]][$dirDet[1]][$fn] = $fn;
                        $sizeOfImage[$fn] = round($file->getSize()/1024);
                    }
                    break;
                case 3 :
                    !isset($images[$dirDet[0]][$dirDet[1]][$dirDet[2]]) ? $images[$dirDet[0]][$dirDet[1]][$dirDet[2]] = array() : null;
                    $finder2->depth('== 0')->files()->in($this->container->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                    foreach ($finder2 as $file) {
                        $fn = $file->getFilename();
                        $images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$fn] = $fn;
                        $sizeOfImage[$fn] = round($file->getSize()/1024);
                    }
                    break;
                case 4 :
                    !isset($images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$dirDet[3]]) ? $images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$dirDet[3]] = array() : null;
                    $finder2->depth('== 0')->files()->in($this->container->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                    foreach ($finder2 as $file) {
                        $fn = $file->getFilename();
                        $images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$dirDet[3]][$fn] = $fn;
                        $sizeOfImage[$fn] = round($file->getSize()/1024);
                    }
                    break;
            }
        }
        $options = ['images' => $images, 'sizeOfImage' => $sizeOfImage];
        return $options;
    }
    
    
}
