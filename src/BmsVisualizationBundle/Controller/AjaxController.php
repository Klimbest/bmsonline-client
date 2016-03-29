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

    public function addPageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            //pobranie danych przesłanych żądaniem
            $height = $request->get("height");
            $width = $request->get("width");
            $name = $request->get("name");
            //dodanie nowej strony
            $page = new Page();
            $page->setHeight($height)
                    ->setWidth($width)
                    ->setName($name);

            $em->persist($page);
            $em->flush();

            $page_id = $page->getId();

            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $pages = $pageRepo->findAll();

            $template = $this->container->get('templating')->render('BmsVisualizationBundle::page.html.twig', ['pages' => $pages, 'page_id' => $page_id]);

            return new JsonResponse(array('ret' => $template));
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function deletePageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $page_id = $request->get("page_id");
            if ($page_id == 1) {
                $ret['result'] = "Nie można usunąć strony głównej";
            } else {
                $em = $this->getDoctrine()->getManager();
                $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
                $page = $pageRepo->find($page_id);
                $panels = $page->getPanels();
                foreach ($panels as $panel) {
                    $em->remove($panel);
                }
                $em->flush();
                $em->remove($page);
                $em->flush();
                $em->getConnection()->exec("ALTER TABLE panel AUTO_INCREMENT = 1;");
                $em->getConnection()->exec("ALTER TABLE page AUTO_INCREMENT = 1;");
                $ret['result'] = "Usunięto stronę: id = " . $page_id . ", wraz ze wszystkimi panelami na tej stronie.";
            }
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function editPageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $page_id = $request->get("page_id");
            $em = $this->getDoctrine()->getManager();
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $page = $pageRepo->find($page_id);

            $height = $request->get("height");
            $width = $request->get("width");
            $name = $request->get("name");

            $page->setHeight($height)
                    ->setWidth($width)
                    ->setName($name);
            $em->flush();
            $ret['page_id'] = $page_id;
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function changePageAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $page_id = $request->get("page_id");
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $registerCDRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:RegisterCurrentData');
            $termRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Term');
            $pages = $pageRepo->findAll();

            $vpanels = $panelRepo->findByType("variable");
            $registers = array();
            foreach ($vpanels as $vpanel) {
                $register = $registerCDRepo->find($vpanel->getContent());
                $registers[$register->getRegister()->getId()] = $register->getFixedValue();
            }

            $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle::page.html.twig', ['pages' => $pages, 'page_id' => $page_id]);
            $ret['registers'] = $registers;
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }
    //Panel adding
    public function addPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $type = $request->get("type");
            $page_id = $request->get("page_id");

            $pageRepo = $this->getDoctrine()->getRepository("BmsVisualizationBundle:Page");
            $page = $pageRepo->find($page_id);
            $pages = $pageRepo->findAll();
            $panel = new Panel();
            switch ($type) {
                case "area" :
                    $panel = $this->setAreaValue($panel, $page, $type);
                    break;
                case "text" :
                    $panel = $this->setTextValue($panel, $page, $type);
                    break;
                case "image" :
                    $panel = $this->setImageValue($panel, $page, $type);
                    break;
                case "variable" :
                    $panel = $this->setVariableValue($panel, $page, $type);
                    break;
                case "navigation" :
                    $panel = $this->setNavigationValue($panel, $page, $type);
                    break;
            }

            $ret["panel_id"] = $panel->getId();
            $ret["type"] = $panel->getType();
            $ret["template"] = $this->container->get('templating')->render('BmsVisualizationBundle::panel.html.twig', ['pages' => $pages, 'panel' => $panel]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }
    //Panel editing
    public function editPanelAction(Request $request) {
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

    public function editAreaPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $panel_id = $request->get("panel_id");
            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel = $panelRepo->find($panel_id);

            $css["backgroundColor"] = $backgroundColor = $request->get("backgroundColor");
            $css["borderColor"] = $borderColor = $request->get("borderColor");
            $css["borderWidth"] = $borderWidth = $request->get("borderWidth");
            $css["borderStyle"] = $borderStyle = $request->get("borderStyle");
            $css["borderRadius"] = $borderRadius = $request->get("borderRadius");
            $css["height"] = $height = $request->get("height");
            $css["width"] = $width = $request->get("width");
            $css["top"] = $topPosition = $request->get("topPosition");
            $css["left"] = $leftPosition = $request->get("leftPosition");

            $panel->setBackgroundColor($backgroundColor)
                    ->setBorderColor($borderColor)
                    ->setBorderStyle($borderStyle)
                    ->setBorderWidth($borderWidth)
                    ->setBorderRadius($borderRadius)
                    ->setHeight($height)
                    ->setWidth($width)
                    ->setLeftPosition($leftPosition)
                    ->setTopPosition($topPosition);

            $em->flush();

            $css["borderWidth"] .= "px";
            $css["height"] .= "px";
            $css["width"] .= "px";
            $css["left"] .= "px";
            $css["top"] .= "px";
            $ret["css"] = $css;

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function editTextPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $panel_id = $request->get("panel_id");
            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel = $panelRepo->find($panel_id);

            $ret["content"] = $content = $request->get("content");
            $css["width"] = $width = $request->get("width");
            $css["height"] = $css["lineHeight"] = $height = $request->get("height");
            $ret["textAlign"] = $textAlign = "text-" . $request->get("textAlign");
            $css["fontWeight"] = $fontWeight = $request->get("fontWeight");
            $css["textDecoration"] = $textDecoration = $request->get("textDecoration");
            $css["fontStyle"] = $fontStyle = $request->get("fontStyle");
            $css["fontFamily"] = $fontFamily = $request->get("fontFamily");
            $css["fontSize"] = $fontSize = $request->get("fontSize");
            $css["color"] = $fontColor = $request->get("fontColor");
            $css["top"] = $topPosition = $request->get("topPosition");
            $css["left"] = $leftPosition = $request->get("leftPosition");

            $panel->setContent($content)
                    ->setWidth($width)
                    ->setHeight($height)
                    ->setTextAlign($textAlign)
                    ->setFontWeight($fontWeight)
                    ->setTextDecoration($textDecoration)
                    ->setFontStyle($fontStyle)
                    ->setFontFamily($fontFamily)
                    ->setFontSize($fontSize)
                    ->setFontColor($fontColor)
                    ->setLeftPosition($leftPosition)
                    ->setTopPosition($topPosition);

            $em->flush();

            $css["left"] .= "px";
            $css["top"] .= "px";
            $ret["css"] = $css;
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function editImagePanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $imagesDir = $this->container->getParameter('kernel.root_dir') . '/../web';
            //get data
            $css['width'] = $width = $request->request->get("width");
            $css['height'] = $height = $request->request->get("height");
            $css["top"] = $topPosition = $request->request->get("topPosition");
            $css["left"] = $leftPosition = $request->request->get("leftPosition");
            $fileName = $request->get("fileName");
            if ($fileName) {

                //save original file
                $img = $request->files->get("file");
                if ($img) {
                    $relativePath = '/images/user/';
                    $resolutionX = $request->request->get("resolutionX");
                    $resolutionY = $request->request->get("resolutionY");
                    $img->move($imagesDir . $relativePath, $fileName);
                    //setFilter
                    $filterConfiguration = $this->container->get('liip_imagine.filter.configuration');
                    $configuration = $filterConfiguration->get('resize');
                    $configuration['filters']['resize']['size'] = array($resolutionX, $resolutionY);
                    $filterConfiguration->set('resize', $configuration);

                    //filter image
                    $imagePath = $relativePath . $fileName;

                    $processedImage = $this->container->get('liip_imagine.data.manager')->find('resize', $imagePath);
                    $filteredImage = $this->container->get('liip_imagine.filter.manager')->applyFilter($processedImage, 'resize')->getContent();
                    //update file    
                    $f = fopen($imagesDir . $relativePath . $fileName, 'w+');
                    fwrite($f, $filteredImage);
                    fclose($f);

                    $ret["content"] = $content = $relativePath . $fileName;
                } else {
                    $finder = new Finder();
                    $finder->files()->name($fileName)->in($this->container->getParameter('kernel.root_dir') . '/../web/images/');
                    foreach ($finder as $file) {
                        $relativePath = $file->getRelativePathname();
                    }
//                    $rel = explode("\\", $relativePath);
                    $relativePath = "/images/" . $relativePath;
//                    foreach ($rel as $r) {
//                        $relativePath = $relativePath . "/" . $r;
//                    }
                    $ret["content"] = $content = $relativePath;
                }
            } else {
                $ret["content"] = $content = null;
            }

            $ret["panel_id"] = $panel_id = $request->request->get("panel_id");
            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel = $panelRepo->find($panel_id);

            $panel->setContent($content)
                    ->setWidth($width)
                    ->setHeight($height)
                    ->setLeftPosition($leftPosition)
                    ->setTopPosition($topPosition);

            $em->flush();

            $ret["css"] = $css;

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function editVariablePanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $panel_id = $request->get("panel_id");
            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $panel = $panelRepo->find($panel_id);

            $ret["content"] = $content = $request->get("content");
            $css["width"] = $width = $request->get("width");
            $css["height"] = $css["lineHeight"] = $height = $request->get("height");
            $ret["textAlign"] = $textAlign = "text-" . $request->get("textAlign");
            $css["fontWeight"] = $fontWeight = $request->get("fontWeight");
            $css["textDecoration"] = $textDecoration = $request->get("textDecoration");
            $css["fontStyle"] = $fontStyle = $request->get("fontStyle");
            $css["fontFamily"] = $fontFamily = $request->get("fontFamily");
            $css["fontSize"] = $fontSize = $request->get("fontSize");
            $css["color"] = $fontColor = $request->get("fontColor");
            $css["top"] = $topPosition = $request->get("topPosition");
            $css["left"] = $leftPosition = $request->get("leftPosition");

            $panel->setContent($content)
                    ->setWidth($width)
                    ->setHeight($height)
                    ->setTextAlign($textAlign)
                    ->setFontWeight($fontWeight)
                    ->setTextDecoration($textDecoration)
                    ->setFontStyle($fontStyle)
                    ->setFontFamily($fontFamily)
                    ->setFontSize($fontSize)
                    ->setFontColor($fontColor)
                    ->setLeftPosition($leftPosition)
                    ->setTopPosition($topPosition);

            $em->flush();

            $css["left"] .= "px";
            $css["top"] .= "px";
            $ret["css"] = $css;

            $register = $registerRepo->find($content);
            $ret["fixedValue"] = $register->getRegisterCurrentData()->getFixedValue();

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function editNavigationPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $panel_id = $request->get("panel_id");
            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel = $panelRepo->find($panel_id);

            $ret["content"] = $content = $request->get("content");
            $css["width"] = $width = $request->get("width");
            $css["height"] = $css["lineHeight"] = $height = $request->get("height");
            $css["top"] = $topPosition = $request->get("topPosition");
            $css["left"] = $leftPosition = $request->get("leftPosition");
            $css["backgroundColor"] = $backgroundColor = $request->get("backgroundColor");
            $css["borderColor"] = $borderColor = $request->get("borderColor");
            $css["borderWidth"] = $borderWidth = $request->get("borderWidth");
            $css["borderStyle"] = $borderStyle = $request->get("borderStyle");
            $css["borderRadius"] = $borderRadius = $request->get("borderRadius");


            $panel->setContent($content)
                    ->setWidth($width)
                    ->setHeight($height)
                    ->setLeftPosition($leftPosition)
                    ->setTopPosition($topPosition)
                    ->setBackgroundColor($backgroundColor)
                    ->setBorderColor($borderColor)
                    ->setBorderStyle($borderStyle)
                    ->setBorderWidth($borderWidth)
                    ->setBorderRadius($borderRadius);

            $em->flush();

            $css["left"] .= "px";
            $css["top"] .= "px";
            $ret["css"] = $css;

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }
    //Dialog loading
    public function loadSettingsPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $panel_id = $request->get("panel_id");
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $termRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Term');
            $panel = $panelRepo->find($panel_id);
            $panelType = $panel->getType();
            $terms = $termRepo->findAllForPanel($panel_id);
            switch($panelType){
                case "area" :
                    $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:dialogAreaPanelSettings.html.twig');
                    break;
                case "text" :
                    $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:dialogTextPanelSettings.html.twig', ['terms' => $terms]);
                    break;
                case "image" :
                    $ret['template'] = $this->getImageSettings();
                    break;
                case "variable" :
                    $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
                    $registers = $registerRepo->findAll();
                    $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:dialogVariablePanelSettings.html.twig', ['registers' => $registers]);
                    break;
                case "navigation" :
                    $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
                    $pages = $pageRepo->findAll();
                    $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:dialogNavigationPanelSettings.html.twig', ['pages' => $pages]);
                    break;
            }
            
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }
    
    //Other
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

            $ret["panel_id"] = $newPanel->getId();
            $ret["type"] = $newPanel->getType();
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

    //Value setting
    private function setAreaValue(Panel $p, Page $page, $type) {

        $em = $this->getDoctrine()->getManager();

        $p->setBackgroundColor("rgba(128, 128, 128, 0.5 )")
                ->setBorderColor("rgba(0, 0, 0, 1)")
                ->setBorderWidth(1)
                ->setBorderStyle("solid")
                ->setHeight(150)
                ->setWidth(300)
                ->setLeftPosition(0)
                ->setTopPosition(0)
                ->setOpacity(1)
                ->setType($type)
                ->setPage($page)
                ->setZIndex(0);

        $em->persist($p);
        $em->flush();
        return $p;
    }

    private function setTextValue(Panel $p, Page $page, $type) {
        $em = $this->getDoctrine()->getManager();

        $p->setHeight(50)
                ->setWidth(200)
                ->setLeftPosition(0)
                ->setTopPosition(0)
                ->setFontFamily("Arial")
                ->setTextAlign("text-center")
                ->setContent("Dodaj tekst")
                ->setType($type)
                ->setPage($page)
                ->setZIndex(7);

        $em->persist($p);
        $em->flush();
        return $p;
    }

    private function setImageValue(Panel $p, Page $page, $type) {
        $em = $this->getDoctrine()->getManager();

        $p->setHeight(100)
                ->setWidth(100)
                ->setLeftPosition(0)
                ->setTopPosition(0)
                ->setType($type)
                ->setPage($page)
                ->setZIndex(3);

        $em->persist($p);
        $em->flush();
        return $p;
    }

    private function setVariableValue(Panel $p, Page $page, $type) {
        $em = $this->getDoctrine()->getManager();

        $p->setHeight(50)
                ->setWidth(50)
                ->setLeftPosition(0)
                ->setTopPosition(0)
                ->setTextAlign("text-center")
                ->setBorderColor("rgba(0, 0, 0, 1)")
                ->setBorderWidth(0)
                ->setBorderStyle("solid")
                ->setContent(18)
                ->setType($type)
                ->setPage($page)
                ->setZIndex(10);

        $em->persist($p);
        $em->flush();
        return $p;
    }

    private function setNavigationValue(Panel $p, Page $page, $type) {
        $em = $this->getDoctrine()->getManager();

        $p->setBackgroundColor("rgba(200, 200, 200, 0.4 )")
                ->setHeight(50)
                ->setWidth(100)
                ->setLeftPosition(0)
                ->setTopPosition(0)
                ->setTextAlign("text-center")
                ->setBorderColor("rgba(0, 0, 0, 1)")
                ->setBorderWidth(1)
                ->setBorderStyle("solid")
                ->setContent(2)
                ->setType($type)
                ->setPage($page)
                ->setZIndex(15);

        $em->persist($p);
        $em->flush();
        return $p;
    }

}
