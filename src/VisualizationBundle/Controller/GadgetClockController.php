<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * Lists all GadgetClock entities.
     *
     * @Route("/", name="gadgetclock_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $gadgetClocks = $em->getRepository('VisualizationBundle:GadgetClock')->findAll();

        return $this->render('gadgetclock/index.html.twig', array(
            'gadgetClocks' => $gadgetClocks,
        ));
    }

    /**
     * Creates a new GadgetClock entity.
     *
     * @Route("/new", name="gadgetclock_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $gadgetClock = new GadgetClock();
        $form = $this->createForm('VisualizationBundle\Form\GadgetClockType', $gadgetClock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetClock);
            $em->flush();

            return $this->redirectToRoute('gadgetclock_show', array('id' => $gadgetClock->getId()));
        }

        return $this->render('gadgetclock/new.html.twig', array(
            'gadgetClock' => $gadgetClock,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a GadgetClock entity.
     *
     * @Route("/{id}", name="gadgetclock_show")
     * @Method("GET")
     */
    public function showAction(GadgetClock $gadgetClock)
    {
        $deleteForm = $this->createDeleteForm($gadgetClock);

        return $this->render('gadgetclock/show.html.twig', array(
            'gadgetClock' => $gadgetClock,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing GadgetClock entity.
     *
     * @Route("/{id}/edit", name="gadgetclock_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, GadgetClock $gadgetClock)
    {
        $deleteForm = $this->createDeleteForm($gadgetClock);
        $editForm = $this->createForm('VisualizationBundle\Form\GadgetClockType', $gadgetClock);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetClock);
            $em->flush();

            return $this->redirectToRoute('gadgetclock_edit', array('id' => $gadgetClock->getId()));
        }

        return $this->render('gadgetclock/edit.html.twig', array(
            'gadgetClock' => $gadgetClock,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a GadgetClock entity.
     *
     * @Route("/{id}", name="gadgetclock_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, GadgetClock $gadgetClock)
    {
        $form = $this->createDeleteForm($gadgetClock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($gadgetClock);
            $em->flush();
        }

        return $this->redirectToRoute('gadgetclock_index');
    }

    /**
     * Creates a form to delete a GadgetClock entity.
     *
     * @param GadgetClock $gadgetClock The GadgetClock entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(GadgetClock $gadgetClock)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gadgetclock_delete', array('id' => $gadgetClock->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
