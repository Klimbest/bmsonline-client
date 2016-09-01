<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * Creates a new PanelVariable entity.
     *
     * @Route("/new", name="panelvariable_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $panelVariable = new PanelVariable();
        $form = $this->createForm(PanelVariableType::class, $panelVariable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelVariable);
            $em->flush();

            return $this->redirectToRoute('panelvariable_show', ['id' => $panelVariable->getId()]);
        }

        return $this->render('VisualizationBundle:panelvariable:form.html.twig', [
            'panelVariable' => $panelVariable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing PanelVariable entity.
     *
     * @Route("/{id}/edit", name="panelvariable_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param PanelVariable $panelVariable
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, PanelVariable $panelVariable)
    {
        $form = $this->createForm(PanelVariableType::class, $panelVariable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelVariable);
            $em->flush();

            return $this->redirectToRoute('panelvariable_edit', ['id' => $panelVariable->getId()]);
        }

        return $this->render('VisualizationBundle:panelvariable:form.html.twig', [
            'panelVariable' => $panelVariable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a PanelVariable entity.
     *
     * @Route("/{id}", name="panelvariable_delete")
     * @param PanelVariable $panelVariable
     * @return RedirectResponse
     */
    public function deleteAction(PanelVariable $panelVariable)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($panelVariable);
        $em->flush();

        return $this->redirectToRoute('panelvariable_index');
    }

}