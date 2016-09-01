<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use VisualizationBundle\Entity\GadgetProgressBar;
use VisualizationBundle\Form\GadgetProgressBarType;

/**
 * GadgetProgressBar controller.
 *
 * @Route("/gadgetprogressbar")
 */
class GadgetProgressBarController extends Controller
{

    /**
     * Creates a new GadgetProgressBar entity.
     *
     * @Route("/new", name="gadgetprogressbar_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $gadgetProgressBar = new GadgetProgressBar();
        $form = $this->createForm(GadgetProgressBarType::class, $gadgetProgressBar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetProgressBar);
            $em->flush();

            return $this->redirectToRoute('gadgetprogressbar_show', ['id' => $gadgetProgressBar->getId()]);
        }

        return $this->render('VisualizationBundle:gadgetprogressbar:form.html.twig', [
            'gadgetProgressBar' => $gadgetProgressBar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing GadgetProgressBar entity.
     *
     * @Route("/{id}/edit", name="gadgetprogressbar_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param GadgetProgressBar $gadgetProgressBar
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, GadgetProgressBar $gadgetProgressBar)
    {
        $form = $this->createForm(GadgetProgressBarType::class, $gadgetProgressBar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetProgressBar);
            $em->flush();

            return $this->redirectToRoute('gadgetprogressbar_edit', ['id' => $gadgetProgressBar->getId()]);
        }

        return $this->render('VisualizationBundle:gadgetprogressbar:form.html.twig', [
            'gadgetProgressBar' => $gadgetProgressBar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a GadgetProgressBar entity.
     *
     * @Route("/{id}", name="gadgetprogressbar_delete")
     * @param GadgetProgressBar $gadgetProgressBar
     * @return RedirectResponse
     */
    public function deleteAction(GadgetProgressBar $gadgetProgressBar)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($gadgetProgressBar);
        $em->flush();

        return $this->redirectToRoute('gadgetprogressbar_index');
    }

}