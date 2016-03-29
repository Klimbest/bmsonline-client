<?php

namespace BmsVisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsVisualizationBundle\Entity\Page;
use BmsVisualizationBundle\Entity\Panel;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;

class AjaxController extends Controller {

/*    public function editImagePanelAction(Request $request) {
//        if ($request->isXmlHttpRequest()) {
//
//            $imagesDir = $this->container->getParameter('kernel.root_dir') . '/../web';
//            //get data
//            $css['width'] = $width = $request->request->get("width");
//            $css['height'] = $height = $request->request->get("height");
//            $css["top"] = $topPosition = $request->request->get("topPosition");
//            $css["left"] = $leftPosition = $request->request->get("leftPosition");
//            $fileName = $request->get("fileName");
//            if ($fileName) {
//
//                //save original file
//                $img = $request->files->get("file");
//                if ($img) {
//                    $relativePath = '/images/user/';
//                    $resolutionX = $request->request->get("resolutionX");
//                    $resolutionY = $request->request->get("resolutionY");
//                    $img->move($imagesDir . $relativePath, $fileName);
//                    //setFilter
//                    $filterConfiguration = $this->container->get('liip_imagine.filter.configuration');
//                    $configuration = $filterConfiguration->get('resize');
//                    $configuration['filters']['resize']['size'] = array($resolutionX, $resolutionY);
//                    $filterConfiguration->set('resize', $configuration);
//
//                    //filter image
//                    $imagePath = $relativePath . $fileName;
//
//                    $processedImage = $this->container->get('liip_imagine.data.manager')->find('resize', $imagePath);
//                    $filteredImage = $this->container->get('liip_imagine.filter.manager')->applyFilter($processedImage, 'resize')->getContent();
//                    //update file    
//                    $f = fopen($imagesDir . $relativePath . $fileName, 'w+');
//                    fwrite($f, $filteredImage);
//                    fclose($f);
//
//                    $ret["content"] = $content = $relativePath . $fileName;
//                } else {
//                    $finder = new Finder();
//                    $finder->files()->name($fileName)->in($this->container->getParameter('kernel.root_dir') . '/../web/images/');
//                    foreach ($finder as $file) {
//                        $relativePath = $file->getRelativePathname();
//                    }
//                    $rel = explode("\\", $relativePath);
//                    $relativePath = "/images/" . $relativePath;
//                    foreach ($rel as $r) {
//                        $relativePath = $relativePath . "/" . $r;
//                    }
//                    $ret["content"] = $content = $relativePath;
//                }
//            } else {
//                $ret["content"] = $content = null;
//            }
//
//            $ret["panel_id"] = $panel_id = $request->request->get("panel_id");
//            $em = $this->getDoctrine()->getManager();
//            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
//            $panel = $panelRepo->find($panel_id);
//
//            $panel->setContent($content)
//                    ->setWidth($width)
//                    ->setHeight($height)
//                    ->setLeftPosition($leftPosition)
//                    ->setTopPosition($topPosition);
//
//            $em->flush();
//
//            $ret["css"] = $css;
//
//            return new JsonResponse($ret);
//        } else {
//            throw new AccessDeniedHttpException();
//        }
//    }
//
//    public function editVariablePanelAction(Request $request) {
//        if ($request->isXmlHttpRequest()) {
//            $panel_id = $request->get("panel_id");
//            $em = $this->getDoctrine()->getManager();
//            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
//            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
//            $panel = $panelRepo->find($panel_id);
//
//            $ret["content"] = $content = $request->get("content");
//            $css["width"] = $width = $request->get("width");
//            $css["height"] = $css["lineHeight"] = $height = $request->get("height");
//            $ret["textAlign"] = $textAlign = "text-" . $request->get("textAlign");
//            $css["fontWeight"] = $fontWeight = $request->get("fontWeight");
//            $css["textDecoration"] = $textDecoration = $request->get("textDecoration");
//            $css["fontStyle"] = $fontStyle = $request->get("fontStyle");
//            $css["fontFamily"] = $fontFamily = $request->get("fontFamily");
//            $css["fontSize"] = $fontSize = $request->get("fontSize");
//            $css["color"] = $fontColor = $request->get("fontColor");
//            $css["top"] = $topPosition = $request->get("topPosition");
//            $css["left"] = $leftPosition = $request->get("leftPosition");
//
//            $panel->setContent($content)
//                    ->setWidth($width)
//                    ->setHeight($height)
//                    ->setTextAlign($textAlign)
//                    ->setFontWeight($fontWeight)
//                    ->setTextDecoration($textDecoration)
//                    ->setFontStyle($fontStyle)
//                    ->setFontFamily($fontFamily)
//                    ->setFontSize($fontSize)
//                    ->setFontColor($fontColor)
//                    ->setLeftPosition($leftPosition)
//                    ->setTopPosition($topPosition);
//
//            $em->flush();
//
//            $css["left"] .= "px";
//            $css["top"] .= "px";
//            $ret["css"] = $css;
//
//            $register = $registerRepo->find($content);
//            $ret["fixedValue"] = $register->getRegisterCurrentData()->getFixedValue();
//
//            return new JsonResponse($ret);
//        } else {
//            throw new AccessDeniedHttpException();
//        }
//    }
//
//    public function editNavigationPanelAction(Request $request) {
//        if ($request->isXmlHttpRequest()) {
//            $panel_id = $request->get("panel_id");
//            $em = $this->getDoctrine()->getManager();
//            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
//            $panel = $panelRepo->find($panel_id);
//
//            $ret["content"] = $content = $request->get("content");
//            $css["width"] = $width = $request->get("width");
//            $css["height"] = $css["lineHeight"] = $height = $request->get("height");
//            $css["top"] = $topPosition = $request->get("topPosition");
//            $css["left"] = $leftPosition = $request->get("leftPosition");
//            $css["backgroundColor"] = $backgroundColor = $request->get("backgroundColor");
//            $css["borderColor"] = $borderColor = $request->get("borderColor");
//            $css["borderWidth"] = $borderWidth = $request->get("borderWidth");
//            $css["borderStyle"] = $borderStyle = $request->get("borderStyle");
//            $css["borderRadius"] = $borderRadius = $request->get("borderRadius");
//
//
//            $panel->setContent($content)
//                    ->setWidth($width)
//                    ->setHeight($height)
//                    ->setLeftPosition($leftPosition)
//                    ->setTopPosition($topPosition)
//                    ->setBackgroundColor($backgroundColor)
//                    ->setBorderColor($borderColor)
//                    ->setBorderStyle($borderStyle)
//                    ->setBorderWidth($borderWidth)
//                    ->setBorderRadius($borderRadius);
//
//            $em->flush();
//
//            $css["left"] .= "px";
//            $css["top"] .= "px";
//            $ret["css"] = $css;
//
//            return new JsonResponse($ret);
//        } else {
//            throw new AccessDeniedHttpException();
//        }
//    }
*/
    
    public function getImageSettings() {
        
        $finder = new Finder();

        $finder->directories()->in($this->container->getParameter('kernel.root_dir') . '/../web/images/');
        $images = array();
        $sizeOfImage = array();
        foreach ($finder as $dir) {
            $finder2 = new Finder();
            $dirDet = explode("/", $dir->getRelativePathname());
            switch (sizeof($dirDet)) {
                case 1 :
                    !isset($images[$dirDet[0]]) ? $images[$dirDet[0]] = array() : null;
                    $finder2->depth('== 0')->files()->in($this->container->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                    foreach ($finder2 as $file) {
                        $fn = $file->getFilename();
                        $images[$dirDet[0]][$fn] = $fn;
                        $sizeOfImage[$fn] = round($file->getSize() / 1024);
                    }
                    break;
                case 2 :
                    !isset($images[$dirDet[0]][$dirDet[1]]) ? $images[$dirDet[0]][$dirDet[1]] = array() : null;
                    $finder2->depth('== 0')->files()->in($this->container->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                    foreach ($finder2 as $file) {
                        $fn = $file->getFilename();
                        $images[$dirDet[0]][$dirDet[1]][$fn] = $fn;
                        $sizeOfImage[$fn] = round($file->getSize() / 1024);
                    }
                    break;
                case 3 :
                    !isset($images[$dirDet[0]][$dirDet[1]][$dirDet[2]]) ? $images[$dirDet[0]][$dirDet[1]][$dirDet[2]] = array() : null;
                    $finder2->depth('== 0')->files()->in($this->container->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                    foreach ($finder2 as $file) {
                        $fn = $file->getFilename();
                        $images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$fn] = $fn;
                        $sizeOfImage[$fn] = round($file->getSize() / 1024);
                    }
                    break;
                case 4 :
                    !isset($images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$dirDet[3]]) ? $images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$dirDet[3]] = array() : null;
                    $finder2->depth('== 0')->files()->in($this->container->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                    foreach ($finder2 as $file) {
                        $fn = $file->getFilename();
                        $images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$dirDet[3]][$fn] = $fn;
                        $sizeOfImage[$fn] = round($file->getSize() / 1024);
                    }
                    break;
            }
        }
        return $this->container->get('templating')->render('BmsVisualizationBundle:dialog:dialogImagePanelSettings.html.twig', ['images' => $images, 'sizeOfImage' => $sizeOfImage]);          
    }

    public function deleteImageFromServerAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $imagesDir = $this->container->getParameter('kernel.root_dir') . '/../web/images/';
            $imageName = $request->get("image_name");

            $finder = new Finder();
            $finder->files()->name($imageName)->in($this->container->getParameter('kernel.root_dir') . '/../web/images/');
            foreach ($finder as $file) {
                $relativePath = $file->getRelativePathname();
            }
            $fs = new Filesystem();

            try {
                $fs->remove($imagesDir . $relativePath);
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at " . $e->getPath();
            }
            return new JsonResponse();
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function loadPanelListAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $panels = $em->createQueryBuilder()
                            ->select('p')
                            ->from('BmsVisualizationBundle:Panel', 'p')
                            ->where("p.page = " . $request->get("page_id"))
//                            ->addOrderBy('p.zIndex', 'DESC')
//                            ->addOrderBy('p.id', 'DESC')
                            ->getQuery()->getResult();

            $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }
}
