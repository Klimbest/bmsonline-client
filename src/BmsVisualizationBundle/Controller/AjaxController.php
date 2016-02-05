<?php

namespace BmsVisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use BmsVisualizationBundle\Entity\Page;
use BmsVisualizationBundle\Entity\Panel;

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

            return new JsonResponse();
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

    public function editPanelAction(Request $request) {
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
            $ret["panel_id"] = $panel_id = $request->get("panel_id");
            $em = $this->getDoctrine()->getManager();
            $panelRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Panel');
            $panel = $panelRepo->find($panel_id);


            $session = $request->getSession();
            $tid = $session->get('target_id');
            $imagesDir = $this->container->getParameter('kernel.root_dir') . '/../web/images/' . $tid . "/";
            $fileName = $request->get("fileName");

            if ($fileName !== "") {
                $fileName .= '.png';
            } else {
                $fileName = uniqid() . '.png';
            }

            if ($panel->getContent() == null) {
                $img = $request->get("file");
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);

                $file = $imagesDir . $fileName;

                file_put_contents($file, $data);

                $fileInfo = getimagesize($file);

                $ret["content"] = $content = "/images/" . $tid . "/" . $fileName;
                $css['width'] = $width = $fileInfo[0];
                $css['height'] = $height = $fileInfo[1];

                $panel->setContent($content)
                        ->setWidth($width)
                        ->setHeight($height);

                $em->flush();

                $ret["css"] = $css;
            } else {
                print_r($imagesDir . $fileName);
                die;
                $file = glob($imagesDir . $fileName);
                print_r($file);
                die;
                $ret['css'] = "css";
                $ret["content"] = "asd";
                $ret["info"] = "Obraz istnieje";
            }
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

    public function loadVariableSettingsPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $registers = $registerRepo->findAll();

            $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:dialogVariablePanelSettings.html.twig', ['registers' => $registers]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function loadNavigationSettingsPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $pages = $pageRepo->findAll();

            $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:dialogNavigationPanelSettings.html.twig', ['pages' => $pages]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function loadImageSettingsPanelAction(Request $request) {
        if ($request->isXmlHttpRequest()) {

            $session = $request->getSession();
            $tid = $session->get('target_id');
            $imagesDir = $this->container->getParameter('kernel.root_dir') . '/../web/images/' . $tid . "/";
            $handle = opendir($imagesDir);
            $images = array();

            while ($file = readdir($handle)) {
                if ($file !== '.' && $file !== '..') {
                    $file = preg_replace('/\..*$/', "", $file);
                    array_push($images, $file);
                }
            }

            $images_path = "/images/" . $tid . "/";
            $ret['template'] = $this->container->get('templating')->render('BmsVisualizationBundle:dialog:dialogImagePanelSettings.html.twig', ['images' => $images, 'path' => $images_path]);
            return new JsonResponse($ret);
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
//            $newLeftPosition = $panel->getLeftPosition() + 75;
//            $newTopPosition = $panel->getTopPosition() + 75;


            $newPanel = clone $panel;
            
            $newPanel->setTopPosition(0)->setLeftPosition(0);
            
//            $newPanel->setTopPosition($newTopPosition)
//                    ->setLeftPosition($newLeftPosition);

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

    private function setAreaValue(Panel $p, Page $page, $type) {

        $em = $this->getDoctrine()->getManager();

        $p->setBackgroundColor("rgba(128, 128, 128, 0.5 )")
                ->setBorderColor("rgba(0, 0, 0, 1)")
                ->setBorderWidth(1)
                ->setBorderStyle("solid")
                ->setHeight(150)
                ->setWidth(300)
                ->setLeftPosition(300)
                ->setTopPosition(200)
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
                ->setLeftPosition(300)
                ->setTopPosition(200)
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

        $p->setHeight(375)
                ->setWidth(500)
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
                ->setLeftPosition(300)
                ->setTopPosition(200)
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
                ->setLeftPosition(300)
                ->setTopPosition(200)
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
