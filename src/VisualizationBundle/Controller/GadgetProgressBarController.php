<?php

namespace VisualizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * Lists all GadgetProgressBar entities.
     *
     * @Route("/", name="gadgetprogressbar_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $gadgetProgressBars = $em->getRepository('VisualizationBundle:GadgetProgressBar')->findAll();

        return $this->render('gadgetprogressbar/index.html.twig', array(
            'gadgetProgressBars' => $gadgetProgressBars,
        ));
    }

    /**
     * Creates a new GadgetProgressBar entity.
     *
     * @Route("/new", name="gadgetprogressbar_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $gadgetProgressBar = new GadgetProgressBar();
        $form = $this->createForm('VisualizationBundle\Form\GadgetProgressBarType', $gadgetProgressBar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetProgressBar);
            $em->flush();

            return $this->redirectToRoute('gadgetprogressbar_show', array('id' => $gadgetProgressBar->getId()));
        }

        return $this->render('gadgetprogressbar/new.html.twig', array(
            'gadgetProgressBar' => $gadgetProgressBar,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a GadgetProgressBar entity.
     *
     * @Route("/{id}", name="gadgetprogressbar_show")
     * @Method("GET")
     */
    public function showAction(GadgetProgressBar $gadgetProgressBar)
    {
        $deleteForm = $this->createDeleteForm($gadgetProgressBar);

        return $this->render('gadgetprogressbar/show.html.twig', array(
            'gadgetProgressBar' => $gadgetProgressBar,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing GadgetProgressBar entity.
     *
     * @Route("/{id}/edit", name="gadgetprogressbar_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, GadgetProgressBar $gadgetProgressBar)
    {
        $deleteForm = $this->createDeleteForm($gadgetProgressBar);
        $editForm = $this->createForm('VisualizationBundle\Form\GadgetProgressBarType', $gadgetProgressBar);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gadgetProgressBar);
            $em->flush();

            return $this->redirectToRoute('gadgetprogressbar_edit', array('id' => $gadgetProgressBar->getId()));
        }

        return $this->render('gadgetprogressbar/edit.html.twig', array(
            'gadgetProgressBar' => $gadgetProgressBar,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a GadgetProgressBar entity.
     *
     * @Route("/{id}", name="gadgetprogressbar_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, GadgetProgressBar $gadgetProgressBar)
    {
        $form = $this->createDeleteForm($gadgetProgressBar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($gadgetProgressBar);
            $em->flush();
        }

        return $this->redirectToRoute('gadgetprogressbar_index');
    }

    /**
     * Creates a form to delete a GadgetProgressBar entity.
     *
     * @param GadgetProgressBar $gadgetProgressBar The GadgetProgressBar entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(GadgetProgressBar $gadgetProgressBar)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gadgetprogressbar_delete', array('id' => $gadgetProgressBar->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
