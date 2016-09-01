<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use VisualizationBundle\Entity\GadgetClock;
use VisualizationBundle\Form\GadgetClockType;

/**
 * GadgetClock controller.
 *
 * @Route("/gadgetclock")
 */
class GadgetClockController extends Controller
{

    /**
     * Creates a new GadgetClock entity.
     *
     * @Route("/new", name="gadgetclock_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $gadgetClock = new GadgetClock();
        $form = $this->createForm(GadgetClockType::class, $gadgetClock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetClock);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $gadgetClock->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:gadgetclock:form.html.twig', [
            'gadgetClock' => $gadgetClock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing GadgetClock entity.
     *
     * @Route("/{id}/edit", name="gadgetclock_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param GadgetClock $gadgetClock
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, GadgetClock $gadgetClock)
    {
        $form = $this->createForm(GadgetClockType::class, $gadgetClock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetClock);
            $em->flush();

            return $this->redirectToRoute('page_show', ['id' => $gadgetClock->getPage()->getId()]);
        }

        return $this->render('VisualizationBundle:gadgetclock:form.html.twig', [
            'gadgetClock' => $gadgetClock,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a GadgetClock entity.
     *
     * @Route("/{id}/delete", name="gadgetclock_delete")
     * @param GadgetClock $gadgetClock
     * @return RedirectResponse
     */
    public function deleteAction(GadgetClock $gadgetClock)
    {
        $page_id = $gadgetClock->getPage()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($gadgetClock);
        $em->flush();

        return $this->redirectToRoute('page_show', ['id' => $page_id]);
    }

}