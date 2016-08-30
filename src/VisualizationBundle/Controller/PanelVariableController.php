<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VisualizationBundle\Entity\PanelVariable;
use VisualizationBundle\Form\PanelVariableType;

/**
 * PanelVariable controller.
 *
 * @Route("/panelvariable")
 */
class PanelVariableController extends Controller
{
    /**
     * Lists all PanelVariable entities.
     *
     * @Route("/", name="panelvariable_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $panelVariables = $em->getRepository('VisualizationBundle:PanelVariable')->findAll();

        return $this->render('panelvariable/index.html.twig', array(
            'panelVariables' => $panelVariables,
        ));
    }

    /**
     * Creates a new PanelVariable entity.
     *
     * @Route("/new", name="panelvariable_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $panelVariable = new PanelVariable();
        $form = $this->createForm('VisualizationBundle\Form\PanelVariableType', $panelVariable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelVariable);
            $em->flush();

            return $this->redirectToRoute('panelvariable_show', array('id' => $panelVariable->getId()));
        }

        return $this->render('panelvariable/new.html.twig', array(
            'panelVariable' => $panelVariable,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PanelVariable entity.
     *
     * @Route("/{id}", name="panelvariable_show")
     * @Method("GET")
     */
    public function showAction(PanelVariable $panelVariable)
    {
        $deleteForm = $this->createDeleteForm($panelVariable);

        return $this->render('panelvariable/show.html.twig', array(
            'panelVariable' => $panelVariable,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PanelVariable entity.
     *
     * @Route("/{id}/edit", name="panelvariable_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PanelVariable $panelVariable)
    {
        $deleteForm = $this->createDeleteForm($panelVariable);
        $editForm = $this->createForm('VisualizationBundle\Form\PanelVariableType', $panelVariable);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelVariable);
            $em->flush();

            return $this->redirectToRoute('panelvariable_edit', array('id' => $panelVariable->getId()));
        }

        return $this->render('panelvariable/edit.html.twig', array(
            'panelVariable' => $panelVariable,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PanelVariable entity.
     *
     * @Route("/{id}", name="panelvariable_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PanelVariable $panelVariable)
    {
        $form = $this->createDeleteForm($panelVariable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($panelVariable);
            $em->flush();
        }

        return $this->redirectToRoute('panelvariable_index');
    }

    /**
     * Creates a form to delete a PanelVariable entity.
     *
     * @param PanelVariable $panelVariable The PanelVariable entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PanelVariable $panelVariable)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('panelvariable_delete', array('id' => $panelVariable->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
