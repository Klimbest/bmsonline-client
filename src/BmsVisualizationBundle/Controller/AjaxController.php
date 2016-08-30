<?php

namespace BmsVisualizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BmsVisualizationBundle\Entity\MyCondition;
use BmsVisualizationBundle\Entity\Effect;
use BmsVisualizationBundle\Entity\Term;

class AjaxController extends Controller
{

    /**
     * @Route("/load_variable_manager", name="bms_visualization_load_variable_manager", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function loadVariableManagerAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $communicationTypeRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:CommunicationType');
            $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $registers = $registerRepo->getAllOrderByName();
            $devices = $deviceRepo->findAll();
            $communicationTypes = $communicationTypeRepo->findAll();
            $options = [
                'registers' => $registers,
                'devices' => $devices,
                'communicationTypes' => $communicationTypes
            ];

            $ret["template"] = $this->get('templating')->render('BmsVisualizationBundle:dialog:variableManager.html.twig', $options);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/load_image_manager", name="bms_visualization_load_image_manager", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function loadImageManagerAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $finder = new Finder();

            $finder->directories()->in($this->getParameter('kernel.root_dir') . '/../web/images/');
            $images = [];
            $sizeOfImage = [];
            foreach ($finder as $dir) {
                $finder2 = new Finder();
                $dirDet = explode("/", $dir->getRelativePathname());
                switch (sizeof($dirDet)) {
                    case 1 :
                        if (!empty($images[$dirDet[0]])) {
                            $images[$dirDet[0]] = [];
                        } else {
                            null;
                        }
                        $finder2->depth('== 0')->files()->in($this->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                        foreach ($finder2 as $file) {
                            $fn = $file->getFilename();
                            $images[$dirDet[0]][$fn] = $fn;
                            $sizeOfImage[$fn] = round($file->getSize() / 1024);
                        }
                        break;
                    case 2 :
                        if (!empty($images[$dirDet[0]][$dirDet[1]])) {
                            $images[$dirDet[0]][$dirDet[1]] = [];
                        } else {
                            null;
                        }
                        $finder2->depth('== 0')->files()->in($this->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                        foreach ($finder2 as $file) {
                            $fn = $file->getFilename();
                            $images[$dirDet[0]][$dirDet[1]][$fn] = $fn;
                            $sizeOfImage[$fn] = round($file->getSize() / 1024);
                        }
                        break;
                    case 3 :
                        if (!empty($images[$dirDet[0]][$dirDet[1]][$dirDet[2]])) {
                            $images[$dirDet[0]][$dirDet[1]][$dirDet[2]] = [];
                        } else {
                            null;
                        }
                        $finder2->depth('== 0')->files()->in($this->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                        foreach ($finder2 as $file) {
                            $fn = $file->getFilename();
                            $images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$fn] = $fn;
                            $sizeOfImage[$fn] = round($file->getSize() / 1024);
                        }
                        break;
                    case 4 :
                        if (!empty($images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$dirDet[3]])) {
                            $images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$dirDet[3]] = [];
                        } else {
                            null;
                        }
                        $finder2->depth('== 0')->files()->in($this->getParameter('kernel.root_dir') . '/../web/images/' . $dir->getRelativePathname());
                        foreach ($finder2 as $file) {
                            $fn = $file->getFilename();
                            $images[$dirDet[0]][$dirDet[1]][$dirDet[2]][$dirDet[3]][$fn] = $fn;
                            $sizeOfImage[$fn] = round($file->getSize() / 1024);
                        }
                        break;
                }
            }

            $ret['template'] = $this->get('templating')->render('BmsVisualizationBundle:dialog:imageManager.html.twig', ['images' => $images, 'sizeOfImage' => $sizeOfImage]);

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/load_effect_css_manager", name="bms_visualization_load_effect_css_manager", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function loadEffectCssManagerAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {


            $ret['template'] = $this->get('templating')->render('BmsVisualizationBundle:dialog:effectCssManager.html.twig');

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/load_event_manager", name="bms_visualization_load_event_manager", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function loadEventManagerAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $deviceRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Device');
            $pageRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Page');
            $panelRepo = $this->getDoctrine()->getRepository('PanelText.php');
//            $effectRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Effect');
//            $myConditionRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:MyCondition');

            $registers = $registerRepo->getAllOrderByName();
            $devices = $deviceRepo->findAll();
            $pages = $pageRepo->findAll();
            $panels = $panelRepo->findAll();

            $options = [
                'registers' => $registers,
                'devices' => $devices,
                'panels' => $panels,
                'pages' => $pages
            ];

            $ret['template'] = $this->get('templating')->render('BmsVisualizationBundle:dialog:eventManager.html.twig', $options);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/create_term", name="bms_visualization_create_term", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function createTermAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $registerRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:Register');
            $bitRegisterRepo = $this->getDoctrine()->getRepository('BmsConfigurationBundle:BitRegister');
            $panelRepo = $this->getDoctrine()->getRepository('PanelText.php');
            $conditionRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:MyCondition');
            $effectRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Effect');
            $termRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Term');
            //get REGISER data
            $registerName = $request->request->get('register_name');
            $register = $registerRepo->findOneBy(['name' => $registerName]);
            if(!isset($register)){
                $register = $bitRegisterRepo->findOneBy(['name' => $registerName]);
            }

            //get PANEL data
            $panel_id = $request->request->get('panel_id');
            $panel = $panelRepo->find($panel_id);
            //get CONDITION data
            $conditionType = $request->get('condition_type');
            $conditionValue = $request->get('condition_value');
            //set CONDITION
            $condition = $conditionRepo->findOneBy(['type' => $conditionType, 'value' => $conditionValue]);
            if (!empty($condition)) {
                $condition = new MyCondition();
                $condition->setType($conditionType)
                    ->setValue($conditionValue)
                    ->setName($conditionType . " -> " . $conditionValue);
                $em->persist($condition);
            }
            //get EFFECT data
            $effectType = $request->get('effect_type');
            $effectContent = $request->get('effect_content');
            //set EFFECT
            $effect = $effectRepo->findOneBy(['type' => $effectType, 'content' => $effectContent]);
            if (!empty($effect)) {
                $effect = new Effect();
                $effect->setType($effectType)
                    ->setContent($effectContent)
                    ->setName($effectType . " -> " . $effectContent);
                $em->persist($effect);
            }
            //set TERM
            $term = new Term();
            $term->setRegister($register)
                ->setPanel($panel)
                ->setCondition($condition)
                ->setEffect($effect)
                ->setName("asd");
            $em->persist($term);

            $em->flush();

            $ret["term"] = $termRepo->findByIdAsArray($term->getId());
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/delete_term", name="bms_visualization_delete_term", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteTermAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $termRepo = $this->getDoctrine()->getRepository('BmsVisualizationBundle:Term');
            $term_id = $request->get("term_id");
            $term = $termRepo->find($term_id);

            $em->remove($term);
            $em->flush();
            $em->getConnection()->exec("ALTER TABLE term AUTO_INCREMENT = 1;");
            $ret['term_id'] = $term_id;
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/add_image", name="bms_visualization_add_image", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function addImageAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
             $imagesDir = $this->getParameter('kernel.root_dir') . '/../web';
            //get data
            $fileName = $request->get("fileName");
            //save original file
            $img = $request->files->get("file");

            $relativePath = '/images/user/';
            $resolutionX = $request->request->get("resolutionX");
            $resolutionY = $request->request->get("resolutionY");
            $img->move($imagesDir . $relativePath, $fileName);
            //setFilter
            $filterConfiguration = $this->get('liip_imagine.filter.configuration');
            $configuration = $filterConfiguration->get('resize');
            $configuration['filters']['resize']['size'] = [$resolutionX, $resolutionY];
            $filterConfiguration->set('resize', $configuration);

            //filter image
            $imagePath = $relativePath . $fileName;

            $processedImage = $this->get('liip_imagine.data.manager')->find('resize', $imagePath);
            $filteredImage = $this->get('liip_imagine.filter.manager')->applyFilter($processedImage, 'resize')->getContent();
            //update file
            $f = fopen($imagesDir . $relativePath . $fileName, 'w+');
            fwrite($f, $filteredImage);
            fclose($f);

            $ret["imageWidth"] = $resolutionX;
            $ret["imageHeight"] = $resolutionY;
            $ret["fileName"] = $fileName;
            $ret["url"] = $imagePath;

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * @Route("/delete_image", name="bms_visualization_delete_image", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteImageFromServerAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $imagesDir = $this->getParameter('kernel.root_dir') . '/../web/images/';
            $imageName = $request->get("image_name");

            $finder = new Finder();
            $finder->files()->name($imageName)->in($this->getParameter('kernel.root_dir') . '/../web/images/');
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

    /**
     * @Route("/load_panel_list", name="bms_visualization_load_panel_list", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function loadPanelListAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $panelRepo = $this->getDoctrine()->getRepository('PanelText.php');
            $panels = $panelRepo->findPanelsForPage($request->get("page_id"));

            $ret['template'] = $this->get('templating')->render('BmsVisualizationBundle::panelList.html.twig', ['panels' => $panels]);
            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }
}