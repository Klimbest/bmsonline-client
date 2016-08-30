<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VisualizationBundle\Entity\PanelText;
use VisualizationBundle\Form\PanelTextType;

/**
 * PanelText controller.
 *
 * @Route("/paneltext")
 */
class PanelTextController extends Controller
{
    /**
     * Lists all PanelText entities.
     *
     * @Route("/", name="paneltext_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $panelTexts = $em->getRepository('VisualizationBundle:PanelText')->findAll();

        return $this->render('paneltext/index.html.twig', array(
            'panelTexts' => $panelTexts,
        ));
    }

    /**
     * Creates a new PanelText entity.
     *
     * @Route("/new", name="paneltext_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $panelText = new PanelText();
        $form = $this->createForm('VisualizationBundle\Form\PanelTextType', $panelText);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelText);
            $em->flush();

            return $this->redirectToRoute('paneltext_show', array('id' => $panelText->getId()));
        }

        return $this->render('paneltext/new.html.twig', array(
            'panelText' => $panelText,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PanelText entity.
     *
     * @Route("/{id}", name="paneltext_show")
     * @Method("GET")
     */
    public function showAction(PanelText $panelText)
    {
        $deleteForm = $this->createDeleteForm($panelText);

        return $this->render('paneltext/show.html.twig', array(
            'panelText' => $panelText,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PanelText entity.
     *
     * @Route("/{id}/edit", name="paneltext_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PanelText $panelText)
    {
        $deleteForm = $this->createDeleteForm($panelText);
        $editForm = $this->createForm('VisualizationBundle\Form\PanelTextType', $panelText);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelText);
            $em->flush();

            return $this->redirectToRoute('paneltext_edit', array('id' => $panelText->getId()));
        }

        return $this->render('paneltext/edit.html.twig', array(
            'panelText' => $panelText,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PanelText entity.
     *
     * @Route("/{id}", name="paneltext_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PanelText $panelText)
    {
        $form = $this->createDeleteForm($panelText);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($panelText);
            $em->flush();
        }

        return $this->redirectToRoute('paneltext_index');
    }

    /**
     * Creates a form to delete a PanelText entity.
     *
     * @param PanelText $panelText The PanelText entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PanelText $panelText)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('paneltext_delete', array('id' => $panelText->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
