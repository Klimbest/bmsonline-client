<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $form = $this->createForm(PanelImageType::class, $panelImage);
        $form->handleRequest($request);
        $images = self::getImages();

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
        $form = $this->createForm(PanelImageType::class, $panelImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelImage);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $panelImage->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:panelimage:form.html.twig', [
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
        $panelImage_new->setTopPosition(0)->setLeftPosition(0);

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

}