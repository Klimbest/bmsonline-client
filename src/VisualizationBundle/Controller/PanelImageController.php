<?php

namespace VisualizationBundle\Controller;

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

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelImage);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $panelImage->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:panelimage:form.html.twig', [
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

}