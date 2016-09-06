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
     * @Route("/page/{page_id}/new", name="gadgetprogressbar_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $gadgetProgressBar = new GadgetProgressBar();
        $page = $this->getDoctrine()->getManager()->getRepository('VisualizationBundle:Page')->find($request->get('page_id'));
        $gadgetProgressBar->setPage($page);
        $form = $this->createForm(GadgetProgressBarType::class, $gadgetProgressBar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetProgressBar);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $gadgetProgressBar->getPage()->getId()]);
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

            return $this->redirectToRoute('page_show', ['id' => $gadgetProgressBar->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:gadgetprogressbar:form.html.twig', [
            'gadgetProgressBar' => $gadgetProgressBar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a GadgetProgressBar entity.
     *
     * @Route("/{id}/delete", name="gadgetprogressbar_delete")
     * @param GadgetProgressBar $gadgetProgressBar
     * @return RedirectResponse
     */
    public function deleteAction(GadgetProgressBar $gadgetProgressBar)
    {
        $page_id = $gadgetProgressBar->getPage()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($gadgetProgressBar);
        $em->flush();

        return $this->redirectToRoute('page_show', ['id' => $page_id]);
    }

    /**
     * Copy a GadgetProgressBar entity.
     *
     * @Route("/{id}/copy", name="gadgetprogressbar_copy")
     * @param GadgetProgressBar $gadgetProgressBar
     * @return RedirectResponse
     */
    public function copyAction(GadgetProgressBar $gadgetProgressBar)
    {
        $gadgetProgressBar->getPage()->getId();
        $gadgetProgressBar_new = clone $gadgetProgressBar;
        $gadgetProgressBar_new->setTopPosition(0)->setLeftPosition(0);

        $em = $this->getDoctrine()->getManager();
        $em->persist($gadgetProgressBar_new);
        $em->flush();

        return $this->redirectToRoute('gadgetprogressbar_edit', ['id' => $gadgetProgressBar_new->getId()]);
    }
}