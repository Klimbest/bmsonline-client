<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use VisualizationBundle\Entity\PanelImage;
use VisualizationBundle\Form\PanelImageType;

/**
 * PanelImage controller.
 *
 * @Route("/panelimage")
 */
class PanelImageController extends Controller
{

    /**
     * Creates a new PanelImage entity.
     *
     * @Route("/page/{page_id}/new", name="panelimage_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $panelImage = new PanelImage();
        $page = $this->getDoctrine()->getManager()->getRepository('VisualizationBundle:Page')->find($request->get('page_id'));
        $panelImage->setPage($page);
        $images = self::getImages();
        $form = $this->createForm(PanelImageType::class, $panelImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelImage);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $panelImage->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:panelimage:form.html.twig', [
            'images' => $images,
            'panelImage' => $panelImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing PanelImage entity.
     *
     * @Route("/{id}/edit", name="panelimage_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param PanelImage $panelImage
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, PanelImage $panelImage)
    {
        $images = self::getImages();
        $form = $this->createForm(PanelImageType::class, $panelImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelImage);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $panelImage->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:panelimage:form.html.twig', [
            'images' => $images,
            'panelImage' => $panelImage,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a PanelImage entity.
     *
     * @Route("/{id}/delete", name="panelimage_delete")
     * @param PanelImage $panelImage
     * @return RedirectResponse
     */
    public function deleteAction(PanelImage $panelImage)
    {
        $page_id = $panelImage->getPage()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($panelImage);
        $em->flush();

        return $this->redirectToRoute('page_show', ['id' => $page_id]);
    }

    /**
     * Copy a PanelImage entity.
     *
     * @Route("/{id}/copy", name="panelimage_copy")
     * @param PanelImage $panelImage
     * @return RedirectResponse
     */
    public function copyAction(PanelImage $panelImage)
    {
        $panelImage->getPage()->getId();
        $panelImage_new = clone $panelImage;

        $em = $this->getDoctrine()->getManager();
        $em->persist($panelImage_new);
        $em->flush();

        return $this->redirectToRoute('panelimage_edit', ['id' => $panelImage_new->getId()]);
    }

    /**
     * @return array
     */
    private function getImages()
    {
        $finder = new Finder();
        $finder->directories()->in($this->getParameter('kernel.root_dir') . '/../web/images/');
        $images = [];
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
                    }
                    break;
            }
        }
        return $images;
    }

    /**
     * @Route("/add_image ", name="send_image_to_server", options={"expose"=true})
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
            $img->move($imagesDir . $relativePath, $fileName);

            $imagePath = $relativePath . $fileName;

//            $processedImage = $this->get('liip_imagine.data.manager')->find('resize', $imagePath);
//            $filteredImage = $this->get('liip_imagine.filter.manager')->applyFilter($processedImage, 'resize')->getContent();
//            //update file
//            $f = fopen($imagesDir . $relativePath . $fileName, 'w+');
//            fwrite($f, $filteredImage);
//            fclose($f);

            $ret["fileName"] = $fileName;
            $ret["url"] = $imagePath;

            return new JsonResponse($ret);
        } else {
            throw new AccessDeniedHttpException();
        }
    }

}