<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use VisualizationBundle\Entity\PanelText;
use VisualizationBundle\Form\PanelTextType;
use VisualizationBundle\Entity\Page;

/**
 * PanelText controller.
 *
 * @Route("/paneltext")
 */
class PanelTextController extends Controller
{

    /**
     * Creates a new PanelText entity.
     *
     * @Route("/page/{page_id}/new", name="paneltext_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $panelText = new PanelText();
        $page = $this->getDoctrine()->getManager()->getRepository('VisualizationBundle:Page')->find($request->get('page_id'));
        $panelText->setPage($page);
        $form = $this->createForm(PanelTextType::class, $panelText);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelText);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $panelText->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:paneltext:form.html.twig', [
            'panelText' => $panelText,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing PanelText entity.
     *
     * @Route("/{id}/edit", name="paneltext_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param PanelText $panelText
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, PanelText $panelText)
    {
        $form = $this->createForm(PanelTextType::class, $panelText);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panelText);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $panelText->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:paneltext:form.html.twig', [
            'panelText' => $panelText,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a PanelText entity.
     *
     * @Route("/{id}/delete", name="paneltext_delete")
     * @param PanelText $panelText
     * @return RedirectResponse
     */
    public function deleteAction(PanelText $panelText)
    {
        $page_id = $panelText->getPage()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($panelText);
        $em->flush();

        return $this->redirectToRoute('page_show', ['id' => $page_id]);
    }

}