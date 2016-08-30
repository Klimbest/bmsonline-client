<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * Lists all PanelImage entities.
     *
     * @Route("/", name="panelimage_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $panelImages = $em->getRepository('VisualizationBundle:PanelImage')->findAll();

        return $this->render('panelimage/index.html.twig', array(
            'panelImages' => $panelImages,
        ));
    }

    /**
     * Creates a new PanelImage entity.
     *
     * @Route("/new", name="panelimage_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $panelImage = new PanelImage();
        $form = $this->createForm('VisualizationBundle\Form\PanelImageType', $panelImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelImage);
            $em->flush();

            return $this->redirectToRoute('panelimage_show', array('id' => $panelImage->getId()));
        }

        return $this->render('panelimage/new.html.twig', array(
            'panelImage' => $panelImage,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PanelImage entity.
     *
     * @Route("/{id}", name="panelimage_show")
     * @Method("GET")
     */
    public function showAction(PanelImage $panelImage)
    {
        $deleteForm = $this->createDeleteForm($panelImage);

        return $this->render('panelimage/show.html.twig', array(
            'panelImage' => $panelImage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PanelImage entity.
     *
     * @Route("/{id}/edit", name="panelimage_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PanelImage $panelImage)
    {
        $deleteForm = $this->createDeleteForm($panelImage);
        $editForm = $this->createForm('VisualizationBundle\Form\PanelImageType', $panelImage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelImage);
            $em->flush();

            return $this->redirectToRoute('panelimage_edit', array('id' => $panelImage->getId()));
        }

        return $this->render('panelimage/edit.html.twig', array(
            'panelImage' => $panelImage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PanelImage entity.
     *
     * @Route("/{id}", name="panelimage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PanelImage $panelImage)
    {
        $form = $this->createDeleteForm($panelImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($panelImage);
            $em->flush();
        }

        return $this->redirectToRoute('panelimage_index');
    }

    /**
     * Creates a form to delete a PanelImage entity.
     *
     * @param PanelImage $panelImage The PanelImage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PanelImage $panelImage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('panelimage_delete', array('id' => $panelImage->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
