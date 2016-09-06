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
     * @Route("/page/{page_id}/new", name="panelvariable_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $panelVariable = new PanelVariable();
        $page = $this->getDoctrine()->getManager()->getRepository('VisualizationBundle:Page')->find($request->get('page_id'));
        $registers = $this->getDoctrine()->getManager()->getRepository('BmsConfigurationBundle:Register')->findAll();
        $panelVariable->setPage($page);
        $panelVariable->setBackgroundColor($page->getBackgroundColor());
        $form = $this->createForm(PanelVariableType::class, $panelVariable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelVariable);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $panelVariable->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:panelvariable:form.html.twig', [
            'panelVariable' => $panelVariable,
            'form' => $form->createView(),
            'registers' => $registers
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
        $registers = $this->getDoctrine()->getManager()->getRepository('BmsConfigurationBundle:Register')->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelVariable);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $panelVariable->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:panelvariable:form.html.twig', [
            'panelVariable' => $panelVariable,
            'form' => $form->createView(),
            'registers' => $registers
        ]);
    }

    /**
     * Deletes a PanelVariable entity.
     *
     * @Route("/{id}/delete", name="panelvariable_delete")
     * @param PanelVariable $panelVariable
     * @return RedirectResponse
     */
    public function deleteAction(PanelVariable $panelVariable)
    {
        $page_id = $panelVariable->getPage()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($panelVariable);
        $em->flush();

        return $this->redirectToRoute('page_show', ['id' => $page_id]);
    }

    /**
     * Copy a PanelVariable entity.
     *
     * @Route("/{id}/copy", name="panelvariable_copy")
     * @param PanelVariable $panelVariable
     * @return RedirectResponse
     */
    public function copyAction(PanelVariable $panelVariable)
    {
        $panelVariable->getPage()->getId();
        $panelVariable_new = clone $panelVariable;
        $panelVariable_new->setTopPosition(0)->setLeftPosition(0);

        $em = $this->getDoctrine()->getManager();
        $em->persist($panelVariable_new);
        $em->flush();

        return $this->redirectToRoute('paneltext_edit', ['id' => $panelVariable_new->getId()]);
    }
}